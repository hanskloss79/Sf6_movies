<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MovieController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    // all movies
    #[Route('/movies', name: 'movies')]
    public function index(): Response
    {
        $repository = $this->em->getRepository(Movie::class);
        // findAll() - SELECT * FROM movie
        $movies = $repository->findAll();
        // find(ID) - SELECT * FROM movie WHERE id = ID
        $movies = $repository->find(5);
        // findBy() - SELECT * FROM movie ORDER BY id DESC
        $movies = $repository->findBy([], ['id' => 'DESC']);
        // findOneBy() - SELECT * FROM movie WHERE id = 5 AND title = 'Dark Knight' ORDER BY id DESC
        $movies = $repository->findOneBy(['id' => 5, 'title' => 'Dark Knight'], ['id' => 'DESC']);
        // count() - SELECT COUNT() FROM movie WHERE id = 5
        $movies = $repository->count(['id' => 5]);

        $movies = $repository->getClassName();
        
        //dd($movies);

        return $this->render('index.html.twig');
    }

    /* inny sposób użycia Repository 
    public function index(MovieRepository $movieRepository): Response
    {
        $movies = $movieRepository->findAll();
        dd($movies);

        return $this->render('index.html.twig');
    } 
    */
    
    
    
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
