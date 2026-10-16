<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractWebTestCase extends WebTestCase
{

    const string URL_HOME = '/';
    const string URL_LOGIN = '/login';
    const string URL_FAVORITE = '/favoris';
    const string URL_ARTIST = '/artiste';
    const string URL_ALBUM = '/album';
    const string URL_PROFILE = '/profil';
    const string URL_REGISTER = '/register';
    const string URL_GENRE = '/genre';
    const string URL_SEARCH = '/search';
    const string URL_ADMIN_ARTIST = '/admin/artiste';
    const string URL_PLAYLIST_CREATE = '/playlistCreate';

    protected function loginUser(
        KernelBrowser $client,
        string        $email
    ): void
    {
        $userRepo = $this->getRepository(UserRepository::class);
        $client->loginUser($userRepo->findOneBy(['email' => $email]));
    }

    protected function getRepository(string $repoClass): ServiceEntityRepository {
        return $this->getContainer()->get($repoClass);
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        return $this->getContainer()->get(EntityManagerInterface::class);
    }

    protected function DataSubmit(
        KernelBrowser $client,
        string        $url,
        string        $button,
        array         $fields
    ): void
    {
        $crawler = $client->request('GET', $url);
        $form = $crawler->selectButton($button)->form();

        $client->submit($form, $fields);
    }

    protected function submitRegistration(
        KernelBrowser $client,
        string        $email,
        string        $password,
        string        $confirmation,
        bool          $agreeTerms
    ): void
    {
        $this->DataSubmit($client, self::URL_REGISTER, "S'inscrire", [
            'registration_form[email]' => $email,
            'registration_form[plainPassword][first]' => $password,
            'registration_form[plainPassword][second]' => $confirmation,
            'registration_form[agreeTerms]' => $agreeTerms,
        ]);
    }

    protected function removeEntity(object $entity): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($entity);
        $entityManager->flush();
    }
}