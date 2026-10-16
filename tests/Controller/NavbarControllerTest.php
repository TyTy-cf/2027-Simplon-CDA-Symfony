<?php

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;

class NavbarControllerTest extends AbstractWebTestCase
{

    public function testTriGenresDropdown(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::URL_HOME);

        $genres = $crawler->filter('#navbarNavDropdown .dropdown-menu .dropdown-item')->each(function ($node) {
            return trim($node->text());
        });

        $sortedGenres = $genres;
        sort($sortedGenres, SORT_STRING | SORT_FLAG_CASE);

        $this->assertNotEmpty($genres);
        $this->assertSame($sortedGenres, $genres);
    }

}