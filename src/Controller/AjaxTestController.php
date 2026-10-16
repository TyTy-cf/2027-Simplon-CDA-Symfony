<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AjaxTestController extends AbstractController
{
    #[Route('/ajax/test', name: 'app_ajax_test')]
    public function index(): Response
    {
        return $this->json('retourne du json');
    }

    #[Route('/ajax/test_2', name: 'app_ajax_test_2')]
    public function test2(): Response
    {
        $nb = 100;
        return $this->json(['nb' => $nb]);
    }

    #[Route('/ajax/count_genre', name: 'app_ajax_count_genre')]
    public function countGenre(GenreRepository $genreRepository): Response
    {
        $entities = $genreRepository->findAll();
        $total = count($entities);


        return $this->json(['total_genres' => $total]);
    }

    #[Route('/ajax/search_pokemon', name: 'app_ajax_search_pokemon')]
    public function searchPokemon(GenreRepository $genreRepository): Response
    {
        $genre = $genreRepository->findByNameLike("pokemon");
        $found = false;
        if (count($genre) > 0) {
            $found = true;
        }
        return $this->json(['isPokemon' => $found]);
    }

    // public function searchPokemon(GenreRepository $genreRepository): Response
    // {
    //     $pokemon = $genreRepository->findOneBy(['name' => 'pokemon']);
    //     $isPokemon = $pokemon !== null;


    //     return $this->json(['isPokemon' => $isPokemon]);
    // }

    #[Route('/ajax/test-serialization', name: 'app_ajax_test_serialization')]
    public function testSerialization(GenreRepository $genreRepository): Response
    {
        $genres = $genreRepository->findAll();

        return $this->json(['genres' => $genres], 200, [], ['groups' => 'genre:read']);
    }

    #[Route('/ajax/test-serialization-album', name: 'app_ajax_test_serialization_album')]
    public function testSerializationAlbum(AlbumRepository $albumRepository): Response
    {
        $albums = $albumRepository->findAll();

        return $this->json(['albums' => $albums], 200, [], ['groups' => 'album:read']);
    }
}
