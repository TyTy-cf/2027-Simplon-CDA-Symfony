<?php

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\TestWith;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends AbstractWebTestCase
{

    
    #[TestWith(['admin@admin.fr'])]
    public function testAccessOKToBackoffice(string $email): void
    {
        $client = static::createClient();
        $this->loginUser($client, $email);

        $client->request('GET', self::URL_BACKOFFICE);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Dashboard');
    }

    public function testAccessKOToBackoffice(): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL_BACKOFFICE);

        $this->assertResponseRedirects(self::URL_LOGIN);
    }
}
