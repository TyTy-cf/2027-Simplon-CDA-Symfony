<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Repository\ArtistRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/artiste', name: 'app_artist_')]
final class ArtistController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(ArtistRepository $artistRepository, Request $request, PaginatorInterface $paginator): Response
    {
        // $getParam = $request->query->get('toto');
        // dump($getParam);

        $pagination = $paginator->paginate(
            //Query créée dans le repository
            $artistRepository->getAll(),
            //Paramètre $GET_['page'] => par défaut 1 s'il n'existe pas
            $request->query->get('page', 1),
            //limite 5
            5
        );


        return $this->render('artist/index.html.twig', [
            'artists' => $pagination,
        ]);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(Artist $artist): Response
    {
        return $this->render('artist/show.html.twig', [
            'artist' => $artist,
        ]);
    }
}
