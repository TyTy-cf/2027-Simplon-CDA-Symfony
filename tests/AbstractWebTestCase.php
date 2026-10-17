<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractWebTestCase extends WebTestCase
{
    const string URL_HOME = '/';
    const string URL_LOGIN = '/login';
    const string URL_FAVORITE = '/favoris';
    const string URL_ARTIST = '/artiste';
    const string URL_ARTIST_ADMIN = '/admin/artiste';
    const string URL_ALBUM = '/album';
    const string URL_ALBUM_ADMIN = '/admin/album';
    const string URL_PROFILE = '/profil';
    const string URL_REGISTER = '/register';
    const string URL_GENRE = '/genre';

    protected function loginUser(
        KernelBrowser $client,
        string        $email
    ): void {
        $userRepo = $this->getRepository(UserRepository::class);
        $client->loginUser($userRepo->findOneBy(['email' => $email]));
    }

    protected function getRepository(string $repoClass): ServiceEntityRepository
    {
        return $this->getContainer()->get($repoClass);
    }
}
