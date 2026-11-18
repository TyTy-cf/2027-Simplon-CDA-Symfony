<?php

namespace App\Controller;

use App\Entity\History;
use App\Entity\User;
use App\Repository\TrackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HistoryController extends AbstractController
{
    #[Route('/history/{id}', name: 'app_history')]
    public function item($id, TrackRepository $trackRepository, EntityManagerInterface $entityManagerInterface): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $track = $trackRepository->find($id);
        /** @var User $user */
        $user = $this->getUser();
        $history = new History();
        $history->setCreatedAt(new \DateTimeImmutable());
        $history->setTrack($track);
        $history->setUser($user);
        $entityManagerInterface->persist($history);
        $entityManagerInterface->flush();

        return $this->json(['listened' => true]);
    }
}
