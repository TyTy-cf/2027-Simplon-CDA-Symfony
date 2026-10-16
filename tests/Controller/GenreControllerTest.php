<?php

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\TestWith;

class GenreControllerTest extends AbstractWebTestCase
{

    #[TestWith([12, 'Pop'])]
    #[TestWith([13, 'Jazz'])]
    #[TestWith([14, 'Rock'])]
    /* ON EST BIEN SUR LA PAGE D'UN GENRE AVEC L'ID ET LE NOM */
    public function testAccessOK(int $genreId, string $name): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL_GENRE . '/' . $genreId);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $name);
    }

    /* GENRE INCONNU = REDIRECTION SUR LA PAGE ALBUM */
    public function testUnknownGenreRedirectsToHome(): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL_GENRE . '/999999');

        $this->assertResponseRedirects(self::URL_HOME);
    }
}