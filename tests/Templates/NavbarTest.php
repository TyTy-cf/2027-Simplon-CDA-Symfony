<?php

namespace App\Tests\Templates;

use App\Tests\AbstractWebTestCase;


class NavbarTest extends AbstractWebTestCase
{
    public function testGenreOrder(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::URL_HOME);

        $crawlersItems = $crawler->filter("li > a.dropdown-item");
        $tabGenre = [];
        foreach ($crawlersItems as $item) {
            array_push($tabGenre, $item->textContent);
        }
        $tabGenreSort = $tabGenre;
        sort($tabGenreSort);
        $this->assertTrue($tabGenre === $tabGenreSort);
    }
}
