<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use App\Repository\TrackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AlbumController extends AbstractController
{
    #[Route('/album/{id}', name: 'app_album_item')]
    public function item(int $id, AlbumRepository $albumRepository, TrackRepository $trackRepository): Response
    {
        $album = $albumRepository->find($id);
        if ($album === null) {
            return $this->redirectToRoute('app_home');
        }
        if (count($album->getTracks()) > 0) {
            $duration = (int) $trackRepository->findTotalDuration($album);
        } else {
            $duration = 0;
        }

        return $this->render('album/item.html.twig', [
            'album' => $album,
            'duration' => $duration
        ]);
    }

    #[Route('/album-search', name: 'app_album_search')]
    public function albumSearch(AlbumRepository $albumRepository, Request $request): Response
    {
        $search = $request->query->get('search');
        $albums = $albumRepository->findByTitleLike($search);

        return $this->json((['albums' => $albums]));

    }
}