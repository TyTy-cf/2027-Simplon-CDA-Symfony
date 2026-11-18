<?php

namespace App\Controller;

use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GenreController extends AbstractController
{
    #[Route('/genre/{id}', name: 'app_genre')]
    public function index($id, GenreRepository $genreRepository): Response
    {
        $genre = $genreRepository->find($id);
        if (null === $genre) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('genre/index.html.twig', [
            'genre' => $genre,
        ]);
    }

    #[Route('/genre', name: 'app_genre_list')]
    public function indexList(GenreRepository $genreRepository): Response
    {
        return $this->render('genre/indexList.html.twig', []);
    }
}
