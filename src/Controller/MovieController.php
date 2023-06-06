<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieFormType;
use App\Repository\MovieRepository;
use Doctrine\DBAL\Schema\View;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;

class MovieController extends AbstractController
{
    private $em;
    private $movieRepository;
    public function __construct(MovieRepository $movieRepository, EntityManagerInterface $em)
    {
        $this->movieRepository = $movieRepository;
        $this->em = $em;
    }  
    // all movies
    #[Route('/movies', methods:['GET'], name: 'movies')]
    public function index(): Response
    {
        $movies = $this->movieRepository->findAll();
        return $this->render('movies/index.html.twig', [
          'movies' => $movies  
        ]);
    }
    /////////////////////////////////////////////////////////////
    // create new movie - CREATE operation
    #[Route('/movies/create', name: 'create_movie', )]
    public function create(Request $request): Response
    {       
        $movie = new Movie();
        $form = $this->createForm(MovieFormType::class, $movie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newMovie = $form->getData();

            $imagePath = $form->get('imagePath')->getData();
            if ($imagePath) {
                $newFileName = uniqid() . '.' . $imagePath->guessExtension();

                try {
                    $imagePath->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads',
                        $newFileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }

                // teraz do bazy ododajemy właściwą ścieżkę
                $newMovie->setImagePath('/uploads/' . $newFileName);
            }

            $this->em->persist($newMovie);
            $this->em->flush();

            return $this->redirectToRoute('movies');
        }

        return $this->render('movies/create.html.twig', [
            'form' => $form->createView() 
        ]);
    }
    /////////////////////////////////////////////////////////////
    // edition of movie existing in database - UPDATE operation
    #[Route('/movies/edit/{id}',name: 'edit_movie')]
    public function edit($id, Request $request): Response
    {
        $movie = $this->movieRepository->find($id);
        // formularz powiązany z danym rekoredem z tabeli movie
        $form = $this->createForm(MovieFormType::class, $movie);
        $form->handleRequest($request);
        $imagePath = $form->get('imagePath')->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            if ($imagePath) {
                // handle image upload
                if ($movie->getImagePath() !== null) {
                    if (file_exists($this->getParameter('kernel.project_dir') . '/public' . $movie->getImagePath())) {
                        $this->getParameter('kernel.project_dir') . '/public' . $movie->getImagePath();

                        $newFileName = uniqid() . '.' . $imagePath->guessExtension();
                        try {
                            $imagePath->move(
                                $this->getParameter('kernel.project_dir') . '/public/uploads',
                                $newFileName
                            );
                        } catch (FileException $e) {
                            return new Response($e->getMessage());
                        }

                        $movie->setImagePath('/uploads/' . $newFileName);
                        $this->em->flush();

                        return $this->redirectToRoute('movies');
                    }
                }
            } else {
                $movie->setTitle($form->get('title')->getData());
                $movie->setReleaseYear($form->get('releaseYear')->getData());
                $movie->setDescription($form->get('description')->getData());

                $this->em->flush();
                return $this->redirectToRoute('movies');
            }
            
        }

        return $this->render('movies/edit.html.twig', [
            'movie' => $movie,
            'form' => $form->createView()
        ]);
    }
    //////////////////////////////////////////////////////////////////////
    // remove one movie - DELETE operation
    #[Route('movies/delete/{id}', methods: ['GET', 'DELETE'], name: 'delete_movie')]
    public function delete($id): Response
    {
        $movie = $this->movieRepository->find($id);
        $this->em->remove($movie);
        $this->em->flush();
        
        return $this->redirectToRoute('movies');
    }


    //////////////////////////////////////////////////////////////////////
    // one chosen movie - READ operation
    #[Route('/movies/{id}', methods:['GET'], name: 'show_movie', )]
    public function show($id): Response
    {
        $movie = $this->movieRepository->find($id);
        
        return $this->render('movies/show.html.twig', [
            'movie' => $movie  
        ]);
    }


    /*
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
    */

    /* inny sposób użycia Repository 
    public function index(MovieRepository $movieRepository): Response
    {
        $movies = $movieRepository->findAll();
        dd($movies);

        return $this->render('index.html.twig');
    } 
    */
    
    
    /*
    // one movie
    #[Route('/movies/{name}', name: 'movie', methods:['GET', 'HEAD'])]
    public function show($name): JsonResponse
    {
        return $this->json([
            'message' => $name,
            'path' => 'src/Controller/MovieController.php',
        ]);
    }
    */
    
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
