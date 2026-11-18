<?php

namespace App\Controller;

use App\Entity\Playlist;
use App\Form\PlaylistType;
use App\Repository\PlaylistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PlaylistController extends AbstractController
{
    #[Route('/playlist/{id}', name: 'app_playlist')]
    public function index($id, PlaylistRepository $playlistRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $playlist = $playlistRepository->find($id);
        if (null === $playlist) {
            return $this->redirectToRoute('app_home');
        }
        if (!$playlist->isPublic() && $playlist->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        $duration = 0;
        foreach ($playlist->getTracks() as $track) {
            $duration += $track->getDuration();
        }
        dump($duration);

        return $this->render('playlist/index.html.twig', [
            'playlist' => $playlist,
            'duration' => $duration,
        ]);
    }

    #[Route('/playlistCreate', name: 'app_playlist_create')]
    public function item(EntityManagerInterface $entityManager, Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $playlist = new Playlist();
        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $playlist->setCreatedAt(new \DateTimeImmutable());
            $playlist->setUser($this->getUser());
            $entityManager->persist($playlist);
            $entityManager->flush();

            return $this->redirectToRoute('app_profil', ['id' => $this->getUser()->getId()]);
        }

        return $this->render('playlist/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
