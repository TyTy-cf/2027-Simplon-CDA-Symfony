<?php

namespace App\Tests\Controller\Admin;

use App\Tests\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\TestWith;

class AdminControllerTest extends AbstractWebTestCase
{

    //Avec mail Admin
    #[TestWith(['admin@admin.fr'])]
    public function testAccessOk(string $email): void
    {
        $client = static::createClient();
        $this->loginUser($client, $email);
        $client->request('GET', self::URL_ADMIN);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Dashboard');
    }

    //N'est pas admin
    #[TestWith(['kelly.cassin@example.com', 403])]
    public function testAccessKO(string $email, int $code): void
    {
        $client = static::createClient();
        $this->loginUser($client, $email);
        $client->request('GET', self::URL_ADMIN);

        $this->assertResponseStatusCodeSame($code);

    }

    //Pas connecté redirection login
    public function testAccessNotLoggedIn(): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL_ADMIN);

        $this->assertResponseRedirects(self::URL_LOGIN);
    }
}
