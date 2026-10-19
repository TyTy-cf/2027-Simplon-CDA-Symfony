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
        $page = $request->query->getInt('page', 1);

        $albums = $paginator->paginate(
            $albumRepository->findWithRelations('album'),
            $page,
            5
        );

        $eps = $paginator->paginate(
            $albumRepository->findWithRelations('ep'),
            $page,
            4
        );

        $singles = $paginator->paginate(
            $albumRepository->findWithRelations('single'),
            $page,
            4
        );

        return $this->render('home/index.html.twig', [
            'albums'  => $albums,
            'eps'     => $eps,
            'singles' => $singles,
        ]);
    }
}
