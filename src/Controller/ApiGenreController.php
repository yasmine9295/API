<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiGenreController extends AbstractController
{
    /**
     * @Route("/api/genres", name="app_api_genres", methods={"GET"})
     */
    public function list(GenreRepository $repo , SerializerInterface $serializer)
    {
        $genres = $repo->findAll();
        $resultat=$serializer->serialize(
            $genres, 
            'json',
            [
                'groups' => ['listGenreFull']
            ]
        );
        return new JsonResponse($resultat,200, [], true);
        
    }

        /**
     * @Route("/api/genres/{id}", name="api_genres_show", methods={"GET"})
     */
    public function show(Genre $genre, SerializerInterface $serializer)
    {
        
        $resultat=$serializer->serialize(
            $genre, 
            'json',
            [
                'groups' => ['listGenreSimple']
            ]
        );
        return new JsonResponse($resultat,Response::HTTP_OK, [], true);
        
    }

    /**
     * @Route("/api/genres", name="api_genres_create", methods={"POST"})
     */
    public function create(Request $request , ObjectManager $manager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $data=$request->getContent();
        // $genre=new Genre();
        // $serializer->deserialize($data, Genre::class,'json', ['object_to_populate'=>$genre]);

        //gestion des erreurs de validation
        $genre=$serializer->deserialize($data, Genre::class,'json');
        $errors=$validator->validate($genre);
        if (count($errors) > 0) {
            $errorsJson=$serializer->serialize( $errors,'json');
            return new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST, [], true);
        }
        $manager->persist($genre);
        $manager->flush();

        return new JsonResponse(
            "Le genre a bien été crée",Response::HTTP_CREATED, [
            "location"=>"api/genres/".$genre->getId()
        ], true);
        // ["location"=>$this->generateUrl('api_genres_show', ["id"=> $genre->getId()], UrlGeneratorInterface::ABSOLUTE_URL])
        
        }

     /**
     * @Route("/api/genres/{id}", name="api_genres_delete", methods={"DELETE"})
     */
    public function delete(Genre $genre , ObjectManager $manager)
    {
       $manager->remove($genre);
       $manager->flush();

       return new JsonResponse("le genre a bien été supprimé", Response::HTTP_OK, [], false);
        
    }

      /**
     * @Route("/api/genres/{id}", name="api_genres_update", methods={"DELETE"})
     */
    public function edit(Genre $genre , Request $request , ObjectManager $manager, SerializerInterface $serializer,ValidatorInterface $validator)
    {

        $data=$request->getContent();
        $serializer->deserialize($data, Genre::class,'json',['object_to_populate'=>$genre]);

                //gestion des erreurs de validation
                
                $errors=$validator->validate($genre);
                if (count($errors) > 0) {
                    $errorsJson=$serializer->serialize( $errors,'json');
                    return new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST, [], true);
                }
       $manager->persist($genre);
       $manager->flush();

       return new JsonResponse("le genre a bien été modifié", Response::HTTP_OK, [], true);
        
    }
}