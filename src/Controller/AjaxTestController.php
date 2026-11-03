<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\GenreRepository;
use App\Repository\AlbumRepository;
use Symfony\Component\HttpFoundation\Request;
final class AjaxTestController extends AbstractController
{
    #[Route('/ajax/test', name: 'app_ajax_test')]
    public function index(): Response
    {
        return $this->json('retour en json');
    }

    #[Route('/ajax/test_2', name: 'app_ajax_test_2')]
    public function test2(): Response
    {
        $nb = 6;

        return $this->json(['nb' => $nb]);
    }

    #[Route('/ajax/count_genre', name: 'app_ajax_count_genre')]
    public function countGenre(GenreRepository $genreRepository): Response
    {
        $entities = $genreRepository->findAll();
        $total = count($entities);

        return $this->json(['total_genres' => $total]);
    }

    #[Route('/ajax/check_pokemon_genre', name: 'app_ajax_check_pokemon_genre')]
    public function checkPokemonGenre(GenreRepository $genreRepository): Response
    {
        $genre = $genreRepository->findByNameLike('pokemon');
        dump($genre);
        $found = false;
        if(count($genre) > 0) {
            $found = true;
        }

        return $this->json(['is_pokemon_exist' => $found]);
    }

    #[Route('/ajax/test-serialisation', name: 'app_ajax_test_serialisation')]
    public function testSerialization(GenreRepository $genreRepository): Response
    {
        $genres = $genreRepository->findAll();
        return $this->json(['genres' => $genres], 200, [], ['groups' => ['genre:read']]);
    }

    #[Route('/ajax/test-serialisation-album', name: 'app_ajax_test_serialisation')]
    public function testSerializationAlbum(AlbumRepository $albumRepository): Response
    {
        $albums = $albumRepository->findAll();
        return $this->json(['albums' => $albums], 200, [], ['groups' => ['album:read']]);
    }

    #[Route('/ajax/search-album', name: 'app_ajax_search_album')]
    public function getAlbumsWanted(AlbumRepository $albumRepository, Request $request): Response
    {
        $search = $request->query->get('search');
        $albums = $albumRepository->findByTitleLike($search);
        dump($albums);
        return $this->json(['albums' => $albums], 200, [], ['groups' => ['album:read']]);
//        return $this->json(['is_pokemon_exist' => $found]);
    }
}


