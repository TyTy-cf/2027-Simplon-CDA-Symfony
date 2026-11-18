<?php

namespace App\Controller\Admin;

use App\Entity\Artist;
use App\Form\ArtistType;
use App\Repository\ArtistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/artiste', name: 'admin_artist_')]
#[IsGranted('ROLE_ADMIN')]
final class ArtistController extends AbstractController
{
    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show($id, ArtistRepository $artistRepository): Response
    {
        $artist = $artistRepository->find($id);
        if (null === $artist) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('admin/artist/show.html.twig', [
            'artist' => $artist,
        ]);
    }

    #[Route('', name: 'index')]
    public function index(ArtistRepository $artistRepository): Response
    {
        $artists = $artistRepository->findAll();

        return $this->render('admin/artist/index.html.twig', [
            'artists' => $artists,
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        $artist = new Artist();
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $artist->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($artist);
            $entityManager->flush();

            return $this->redirectToRoute('admin_artist_index');
        }

        return $this->render('admin/artist/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', requirements: ['id' => '\d+'])]
    public function edit(EntityManagerInterface $entityManager, Request $request, $id, ArtistRepository $artistRepository): Response
    {
        $artist = $artistRepository->find($id);
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($artist);
            $entityManager->flush();

            return $this->redirectToRoute('admin_artist_index');
        }

        return $this->render('admin/artist/edit.html.twig', [
            'form' => $form->createView(),
            'artist' => $artist,
        ]);
    }
}
