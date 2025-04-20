<?php

namespace App\Controller;

use App\Repository\GenreRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiGenreController extends AbstractController
{
    /**
     * @Route("/api/genres", name="api_genres", methods={"Get"})
     */
    public function list(GenreRepository $repo, SerializerInterface $serializer): Response
    {
        $genres=$repo->findAll();
        $resultat=$serializer->serialize(
            $genres,
            'json',
            [
                'groups'=>['listGenreSimple']
            ]
        );
        return new JsonResponse($resultat,200,[],true);     
        
    }
}
