<?php
namespace App\Services;

use App\Entity\Pret;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PretSubscriber implements EventSubscriberInterface
{

    private $token;
    public function __construct(TokenStorageInterface $token)
    {
        $this->token=$token;
    }
    public static function getSubscribedEvents()
    {
        return[
            KernelEvents::VIEW => ['getAuthenticatedUser' , EventPriorities::PRE_WRITE]
        ];
    }

    public function getAuthenticatedUser(GetResponseForControllerResultEvent $event)
    {
        $entity = $event->getControllerResult(); //recupere lentité qui a declenché l'evenemt
        $method = $event->getRequest()->getMethod(); // recupere la methode invoquée dans la request
        $adherent =$this->token->getToken()->getUser(); //recupere ladherent actuellement connecté qui a lancé la request
        if($entity instanceof Pret && $method == Request::METHOD_POST){ //si operation post
            $entity->setAdherent($adherent); // ecrire ladhrent dans la propriété adherent de Pret
        }
        return;


    }





}