<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GenreControllerTest extends AbstractWebTestCase
{
    public function testGenreAsAlphabeticOrder(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::URL_HOME);

        $this->assertResponseIsSuccessful();

        $genre = $crawler
        ->filter('#navbarDropdownGenre')

        
    }
}
