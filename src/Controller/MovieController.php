<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    // all movies
    #[Route('/movies', name: 'movies')]
    public function index(): Response
    {
        $movies = ["Inception", "Loki", "Black Widow"];

        return $this->render('index.html.twig', array(
            'movies' => $movies
        ));
    }
    
    
    // one movie
    #[Route('/movies/{name}', name: 'movie', methods:['GET', 'HEAD'])]
    public function show($name): JsonResponse
    {
        return $this->json([
            'message' => $name,
            'path' => 'src/Controller/MovieController.php',
        ]);
    }
    
    // inna metoda definiowania ścieżki używając annotations ale to już przeszłość  
    /**
     * oldMethod
     *
     * @Route("/old", name="old")
     */
    public function oldMethod(): Response
    {
        return $this->json([
            'message' => 'Old method.',
            'path' => 'src/Controller/MovieController.php',
        ]);
    }
}
