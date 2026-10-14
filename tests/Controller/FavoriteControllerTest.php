<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FavoriteControllerTest extends WebTestCase
{

    public function testAccessOK(): void
    {
        $client = static::createClient();

        /** @var UserRepository $userRepo */
        $userRepo = $this->getContainer()->get(UserRepository::class);
        $client->loginUser($userRepo->findOneBy(['email' => 'kelly.cassin@example.com']));

        $client->request('GET', '/favoris');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Mes favoris');
    }

    public function testAccessKO(): void
    {
        $client = static::createClient();
        $client->request('GET', '/favoris');

        $this->assertResponseRedirects('/login');
    }

}
