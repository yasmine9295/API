<?php

namespace App\Serializer;

use App\Entity\Livre;
use Symfony\Component\HttpFoundation\Request;
use ApiPlatform\Serializer\SerializerContextBuilderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class LivreContextBuilder implements SerializerContextBuilderInterface
{
    private $decorated;
    private $authorizationChecker;

    public function __construct(SerializerContextBuilderInterface $decorated, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->decorated = $decorated;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function createFromRequest(Request $request, bool $normalization, ?array $extractedAttributes = null): array
    {
        $context = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);
        $resourceClass = $context['resource_class'] ?? null;

        if ($resourceClass === Livre::class && isset($context['groups']) && $this->authorizationChecker->isGranted('ROLE_MANAGER') &&  $normalization === true) {
            $context['groups'][] = 'get_role_manager';
        }

        if ($resourceClass === Livre::class && isset($context['groups']) && $this->authorizationChecker->isGranted('ROLE_ADMIN') &&  $normalization === false) {
            if ($request->getMethod() == 'PUT'){
                $context['groups'][] = 'put_admin';
                }
            }
          

        return $context;
    }
}