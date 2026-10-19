<?php

namespace App\Controller;

use App\Entity\Album;
use App\Repository\AlbumRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(AlbumRepository $albumRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $albums = $paginator->paginate(
            $albumRepository->findWithRelations('album', 12),
            $request->query->getInt('page', 1),
            5
        );

        $eps = $albumRepository->findWithRelations('ep', 12);
        $singles = $albumRepository->findWithRelations('single', 12);

        return $this->render('home/index.html.twig', [
            'eps' => $eps,
            'singles' => $singles,
            'albums' => $albums, // l'objet paginé, pas le tableau brut
        ]);
    }
}
