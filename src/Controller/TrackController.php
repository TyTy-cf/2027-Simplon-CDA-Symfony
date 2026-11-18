<?php

namespace App\Controller;

use App\Entity\Track;
use App\Form\PlaylistTrackType;
use App\Form\TrackType;
use App\Repository\AlbumRepository;
use App\Repository\PlaylistRepository;
use App\Repository\TrackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TrackController extends AbstractController
{
    #[Route('/track/{id}', name: 'app_track_item')]
    public function item($id, TrackRepository $trackRepository): Response
    {
        $track = $trackRepository->find($id);
        if (null === $track) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('track/item.html.twig', [
            'track' => $track,
        ]);
    }

    #[Route('/trackAdd/{id}', name: 'app_track_add')]
    public function add(EntityManagerInterface $entityManager, Request $request, $id, AlbumRepository $albumRepository): Response
    {
        $track = new Track();
        $album = $albumRepository->find($id);
        if (null === $album) {
            return $this->redirectToRoute('app_home');
        }
        $form = $this->createForm(TrackType::class, $track);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $track->setCreatedAt(new \DateTimeImmutable());
            $track->setAlbum($album);
            $track->setListeningCount(0);
            $entityManager->persist($track);
            $entityManager->flush();

            return $this->redirectToRoute('app_album_item', ['id' => $id]);
        }

        return $this->render('track/add.html.twig', [
            'form' => $form->createView(),
            'album' => $album,
        ]);
    }

    #[Route('/trackEdit/{id}', name: 'app_track_edit')]
    public function edit(EntityManagerInterface $entityManager, Request $request, $id, TrackRepository $trackRepository): Response
    {
        $track = $trackRepository->find($id);
        if (null === $track) {
            return $this->redirectToRoute('app_home');
        }
        $form = $this->createForm(TrackType::class, $track);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($track);
            $entityManager->flush();

            return $this->redirectToRoute('app_track_item', ['id' => $track->getId()]);
        }

        return $this->render('track/edit.html.twig', [
            'form' => $form->createView(),
            'track' => $track,
        ]);
    }

    #[Route('/playlistAddTrack/{id}', name: 'app_track_playlist')]
    public function addToPlaylist($id, TrackRepository $trackRepository, PlaylistRepository $playlistRepository, EntityManagerInterface $entityManagerInterface, Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $track = $trackRepository->find($id);
        $user = $this->getUser();
        $playlist = $user->getPlaylists();
        $form = $this->createForm(PlaylistTrackType::class, $track);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManagerInterface->persist($track);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('app_track_item', ['id' => $track->getId()]);
        }

        return $this->render('playlist/addTrack.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
