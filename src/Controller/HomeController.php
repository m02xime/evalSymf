<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Moovies;


#[Route('/api')]
class HomeController extends AbstractController
{

    private $doctrine;

    //construct
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }


    #[Route('/', name: 'app_home')]
    // page d'acceuil qui revoie tous les films et séries dans une variable $moovies
    public function index(Request $request): Response
    {
        $moovies = $this->doctrine->getRepository(Moovies::class)->findAll();
        return $this->render('home/index.html.twig', [
            'moovies' => $moovies,
        ]);
    }
}
