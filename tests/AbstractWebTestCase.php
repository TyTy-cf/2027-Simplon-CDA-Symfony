<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractWebTestCase extends WebTestCase
{

    const string URL_HOME = '/';
    const string URL_LOGIN = '/login';
    const string URL_FAVORITE = '/favoris';
    const string URL_ARTIST = '/artiste';
    const string URL_ALBUM = '/album';
    const string URL_PROFILE = '/profil';
    const string URL_REGISTER = '/register';
    const string URL_TRACK = '/trackAdd/206';
    const string URL_GENRE = '/genre/22';

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

    protected function removeEntity(?object $object): void
    {
        if ($object !== null) {
            $em = $this->getContainer()->get('doctrine.orm.entity_manager');
            $em->remove($object);
            $em->flush();
        }
    }

    protected function fillAndSendForm(KernelBrowser $client, Crawler $crawler, string $formSelector, array $formData): void
    {
        $form = $crawler->selectButton($formSelector)->form();
        foreach ($formData as $name => $value) {
            $form[$name] = $value;
        }
        $client->submit($form);
    }
}
