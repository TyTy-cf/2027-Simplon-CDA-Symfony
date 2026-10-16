<?php

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\TestWith;

class ArtistControllerTest extends AbstractWebTestCase
{

    public function testAccessOK(): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL_ARTIST);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Artistes');
    }

    #[TestWith([39, 'Woodkid'])]
    #[TestWith([58, 'Durgan Wendy'])]
    #[TestWith([70, 'Buckridge Willie'])]
    /* LES ARTISTES SONT BIEN AFFICHÉS */
    public function testShowDisplaysArtist(int $artistId, string $stageName): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL_ARTIST . '/' . $artistId);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', $stageName);
    }

    /* LA PAGINATION FONCTIONNE ET AFFICHE BIEN DIFFÉRENTS ARTISTES POUR CHAQUE PAGE */
    public function testPaginationShowsDifferentArtists(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', self::URL_ARTIST . '?page=1');
        $Page1 = $crawler->filter('h5.card-title')->first()->text();

        $crawler = $client->request('GET', self::URL_ARTIST . '?page=2');
        $this->assertResponseIsSuccessful();
        $Page2 = $crawler->filter('h5.card-title')->first()->text();

        $this->assertNotSame($Page1, $Page2);
    }

}