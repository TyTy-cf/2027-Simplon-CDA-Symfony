<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class GenreControllerTest extends AbstractWebTestCase
{
    public function testGenreAsAlphabeticOrder(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::URL_HOME);

        $this->assertResponseIsSuccessful();

        $genres = $crawler
        ->filter('#navbarDropdownGenre')
        ->each(fn (Crawler $node) => trim($node->text()));

        $this->assertNotEmpty($genres, 'Aucun genre trouvé dans la navbar.');
        
        $sorted = $genres;
        sort($sorted, SORT_NATURAL | SORT_FLAG_CASE);

        $this->assertSame($sorted, $genres);

        
    }
}
