<?php

namespace App\Controller;

use App\Entity\Favorite;
use App\Entity\User;
use App\Repository\FavoriteRepository;
use App\Repository\TrackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FavoriteController extends AbstractController
{
    #[Route('/favoris', name: 'app_favorite_index')]
    public function index(FavoriteRepository $favoriteRepository): Response
    {
        /** @var User|null $user */
        $user = $this->getUser();

        if (null === $user) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('favorite/index.html.twig', [
            'favorites' => $favoriteRepository->findBy(['user' => $user], ['createdAt' => 'DESC']),
        ]);
    }

    #[Route('/favorite/{id}', name: 'app_favorite')]
    public function item($id, TrackRepository $trackRepository, FavoriteRepository $favoriteRepository, EntityManagerInterface $entityManagerInterface, Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $track = $trackRepository->find($id);
        $user = $this->getUser();
        $favorites = $user->getFavorites();
        $favTracks = [];
        foreach ($favorites as $favorite) {
            array_push($favTracks, $favorite->getTrack());
        }
        if (in_array($track, $favTracks)) {
            $favorite = $favoriteRepository->findOneBy(['user' => $user, 'track' => $track]);
            $entityManagerInterface->remove($favorite);
            $entityManagerInterface->flush();

            return $this->json(['fav' => false]);
        }
        $favorite = new Favorite();
        $favorite->setCreatedAt(new \DateTimeImmutable());
        $favorite->setTrack($track);
        $favorite->setUser($user);
        $entityManagerInterface->persist($favorite);
        $entityManagerInterface->flush();

        return $this->json(['fav' => true]);

        return $this->render('favorite/item.html.twig', []);
    }

    #[Route('/favoriteCheck/{id}', name: 'app_favorite_check')]
    public function check($id, TrackRepository $trackRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $track = $trackRepository->find($id);
        $user = $this->getUser();
        $favorites = $user->getFavorites();
        $favTracks = [];
        foreach ($favorites as $favorite) {
            array_push($favTracks, $favorite->getTrack());
        }
        if (in_array($track, $favTracks)) {
            return $this->json(['fav' => true]);
        }

        return $this->json(['fav' => false]);

        return $this->render('favorite/item.html.twig', []);
    }
}
