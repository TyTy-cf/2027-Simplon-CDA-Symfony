<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testAccessOK(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Se connecter');
    }

    public function testFormExists(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $this->assertSelectorExists('input[name="email"]');
        $this->assertSelectorExists('input[name="password"]');
        $this->assertSelectorExists('input[name="_remember_me"]');
        $this->assertSelectorTextContains('button[type="submit"]', 'Se connecter');
    }

    public function testLogoutExistsAsLoggedUser(): void
    {
        $client = static::createClient();

        /** @var UserRepository $userRepo */
        $userRepo = $this->getContainer()->get(UserRepository::class);
        $client->loginUser($userRepo->findOneBy(['email' => 'kelly.cassin@example.com']));

        $client->request('GET', '/login');

        $this->assertSelectorExists('a[href="/logout"]');
    }

}
