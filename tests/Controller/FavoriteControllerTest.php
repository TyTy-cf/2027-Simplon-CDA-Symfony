<?php

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\TestWith;

class FavoriteControllerTest extends AbstractWebTestCase
{

    #[TestWith(['kelly.cassin@example.com'])]
    #[TestWith(['admin@admin.fr'])]
    public function testAccessOK(string $email): void
    {
        $client = static::createClient();
        $this->loginUser($client, $email);

        $client->request('GET', self::URL_FAVORITE);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Mes favoris');
    }

    public function testAccessKO(): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL_FAVORITE);

        $this->assertResponseRedirects(self::URL_LOGIN);
    }

}
