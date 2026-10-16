<?php

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\TestWith;

class GenreControllerTest extends AbstractWebTestCase
{

    #[TestWith([12, 'Pop'])]
    #[TestWith([13, 'Jazz'])]
    #[TestWith([14, 'Rock'])]
    /* On est bien redirigé si un genre existe et a son id */
    public function testAccessOK(int $genreId, string $name): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL_GENRE . '/' . $genreId);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $name);
    }

    public function testUnknownGenreRedirectsToHome(): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL_GENRE . '/999999');

        $this->assertResponseRedirects(self::URL_HOME);
    }

}