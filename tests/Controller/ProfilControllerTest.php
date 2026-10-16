<?php


namespace App\Tests\Controller;


use App\Tests\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\TestWith;

/**
 * Class ProfilController.php
 *
 * @author Kevin Tourret
 */
class ProfilControllerTest extends AbstractWebTestCase
{

    #[TestWith(['kelly.cassin@example.com'])]
    #[TestWith(['admin@admin.fr'])]
    public function testAccessOK(string $email): void
    {
        $client = static::createClient();
        $this->loginUser($client, $email);

        $client->request('GET', self::URL_PROFILE);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Welcome ' . $email);
    }

    public function testAccessKO(): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL_PROFILE);

        $this->assertResponseRedirects(self::URL_LOGIN);
    }
    
    #[TestWith(['admin@admin.fr'])]

    public function testAccessOkToAdmin(string $email): void
    {
        $client = static::createClient();
        $this->loginUser($client, $email);

        $client->request('GET', self::URL_ADMIN);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Dashboard');
    }
}
