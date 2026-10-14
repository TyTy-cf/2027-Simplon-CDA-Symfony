<?php


namespace App\Tests\Controller;


use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class ProfilController.php
 *
 * @author Kevin Tourret
 */
class ProfilControllerTest extends WebTestCase
{

    public function testAccessOK(): void
    {
        $client = static::createClient();

        /** @var UserRepository $userRepo */
        $userRepo = $this->getContainer()->get(UserRepository::class);
        $client->loginUser($userRepo->findOneBy(['email' => 'kelly.cassin@example.com']));

        $client->request('GET', '/profil');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Welcome ' . 'kelly.cassin@example.com');
    }

    public function testAccessKO(): void
    {
        $client = static::createClient();
        $client->request('GET', '/profil');

        $this->assertResponseRedirects('/login');
    }

}
