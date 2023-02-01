<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Moovies;
use \DateTimeImmutable;


#[Route('/api')]
class MooviesController extends AbstractController
{

    private $doctrine;

    //construct
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }


    //  Créer une route /create permettant de créer un objet (film/série) et de l'ajouter à la base de données qui retourne une réponse 201
    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $entityManager = $this->doctrine->getManager();

        $moovie = new Moovies();
        $moovie->setNom($request->get('nom'));
        $moovie->setSynopsis($request->get('synopsis'));
        $moovie->setType($request->get('type'));
        $moovie->setCreatedAt(new DateTimeImmutable());


        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($moovie);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        // Renvoyez une réponse 201
        return new Response('', Response::HTTP_CREATED);
    }


    // créér une /getall qui retourne la liste de tous les objets (films/séries) de la base de données
    #[Route('/getall', name: 'getall', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $moovies = $this->doctrine->getRepository(Moovies::class)->findAll();
        // convert to json
        $data = [];
        foreach ($moovies as $moovie) {
            $data[] = [
                'id' => $moovie->getId(),
                'nom' => $moovie->getNom(),
                'synopsis' => $moovie->getSynopsis(),
                'type' => $moovie->getType(),
                'createdAt' => $moovie->getCreatedAt(),
            ];
        }
        if (!$data) {
            return new JsonResponse(['message' => 'Aucune donnée trouvée'], 404);
        }

        $response = new JsonResponse(['message' => 'Liste des films/séries trouvés', 'data' => $data]);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    // Créer une /get/{id} qui retourne un objet (film/série) de la base de données en fonction de son id
    #[Route('/get/{id}', name: 'get', methods: ['GET'])]
    public function get($id): JsonResponse
    {
        $moovie = $this->doctrine->getRepository(Moovies::class)->find($id);
        if (!$moovie) {
            return new JsonResponse(['message' => 'Aucune donnée trouvée'], 404);
        }

        // convert to json
        $data = [
            'id' => $moovie->getId(),
            'nom' => $moovie->getNom(),
            'synopsis' => $moovie->getSynopsis(),
            'type' => $moovie->getType(),
            'createdAt' => $moovie->getCreatedAt(),
        ];

        $response = new JsonResponse(['message' => 'Le film/série a bien été trouvé', 'data' => $data]);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
