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
        $pagination = $paginator->paginate(
            $albums = $albumRepository->findWithRelations('album', 12),
            $request->query->get('page', 1),
            5
        );
        $eps = $albumRepository->findWithRelations('ep', 12);

        $singles = $albumRepository->findWithRelations('single', 12);



        return $this->render('home/index.html.twig', [
            'eps' => $eps,
            'singles' => $singles,
            'albums' => $albums,
            'pagination' => $pagination,
        ]);
    }
    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(Album $album): Response
    {
        return $this->render('album/show.html.twig', [
            'album' => $album,
        ]);
    }
}
