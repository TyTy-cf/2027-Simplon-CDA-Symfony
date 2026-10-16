<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\GenreRepository;
use App\Repository\AlbumRepository;

final class AjaxTestController extends AbstractController
{
    #[Route('/ajax/test', name: 'app_ajax_test')]
    public function test(): Response
    {
        return $this->json("data en json");
    }

    #[Route('/ajax/test_2', name: 'app_ajax_test_2')]
    public function test2(): Response
    {
        $nb = 5;

        return $this->json(['nb' => $nb]);
    }

    #[Route('/ajax/count_genre', name: 'app_ajax_count_genre')]
    public function countGenre(GenreRepository $genreRepository): Response
    {
        // rendre nb total de genres
        $entities = $genreRepository->findAll();
        $total = count($entities);

        return $this->json(['total_genres' => $total]);
    }


    #[Route('/ajax/poke', name: 'app_ajax_poke')]
    public function getPoke(GenreRepository $genreRepository): Response
    {
        $poke = $genreRepository->findByNameLike("Pop");

        $found = false;
        if ($poke !== null) {
            $found = true;
        }
        return $this->json(['is_pokemon_exist' => $found]);
    }


    #[Route('/ajax/serialization', name: 'app_ajax_serialization')]
    public function testSerialization(GenreRepository $genreRepository): Response
    {
        $genres = $genreRepository->findAll();

        return $this->json(['genres' => $genres], 200, [], ['groups' => ['genre:read']]);
    }

    #[Route('/ajax/serialization-album', name: 'app_ajax_serialization_album')]
    public function testSerializationAlbum(AlbumRepository $albumRepository): Response
    {
        $albums = $albumRepository->findAll();

        return $this->json(['albums' => $albums], 200, [], ['groups' => ['album:read']]);
    }
}
