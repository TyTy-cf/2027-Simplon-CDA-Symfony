<?php

namespace App\Controller\Admin;

use App\Entity\Album;
use App\Form\AlbumType;
use App\Repository\AlbumRepository;
use App\Repository\ArtistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/album', name: 'admin_album_')]
#[IsGranted('ROLE_ADMIN')]
final class AlbumController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(AlbumRepository $albumRepository): Response
    {
        return $this->render('admin/album/index.html.twig', [
            'albums' => $albumRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(Album $album): Response
    {
        return $this->render('admin/album/show.html.twig', [
            'album' => $album,
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(
        EntityManagerInterface                                    $entityManager,
        Request                                                   $request,
        ArtistRepository                                          $artistRepository,
        SluggerInterface                                          $slugger,
        #[Autowire('%kernel.project_dir%/public/uploads')] string $imgDirectory
    ): Response
    {
        $album = new Album();
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $album->setCreatedAt(new \DateTimeImmutable());
            $img = $form->get('poster')->getData();
            if ($img) {
                $ogFilename = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($ogFilename);
                $newFilename = 'uploads/' . $safeFilename . '-' . bin2hex(random_bytes(16)) . '.' . $img->guessExtension();

                try {
                    $img->move($imgDirectory, $newFilename);
                } catch (FileException $e) {
                }
                $album->setPoster($newFilename);
            }
            $entityManager->persist($album);
            $entityManager->flush();
            return $this->redirectToRoute("admin_album_index");
        }
        return $this->render('admin/album/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', requirements: ['id' => '\d+'])]
    public function edit(
        EntityManagerInterface                                    $entityManager,
        Request                                                   $request,
        Album                                                     $album,
        SluggerInterface                                          $slugger,
        #[Autowire('%kernel.project_dir%/public/uploads')] string $imgDirectory
    ): Response
    {
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $album->setCreatedAt(new \DateTimeImmutable());
            $img = $form->get('poster')->getData();

            if ($img) {
                $ogFilename = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($ogFilename);
                $newFilename = 'uploads/' . $safeFilename . '-' . uniqid() . '.' . $img->guessExtension();

                try {
                    $img->move($imgDirectory, $newFilename);
                } catch (FileException $e) {
                }
                $album->setPoster($newFilename);
            }
            $entityManager->persist($album);
            $entityManager->flush();
            return $this->redirectToRoute("admin_artist_show", ['id' => $album->getArtist()->getId()]);
        }
        return $this->render('admin/album/edit.html.twig', [
            'form' => $form->createView(),
            'album' => $album
        ]);
    }
}
