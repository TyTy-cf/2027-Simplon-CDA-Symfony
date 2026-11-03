<?php

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\TestWith;


class ArtistControllerTest extends AbstractWebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL_ARTIST);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Artistes');
    }

    #[TestWith(["", 0, "Joe Hisaishi"])]
    #[TestWith(["?page=2", 6, "Heidenreich Mathilde"])]
    public function testPaginationNext(string $url, int $linkNumber, string $result): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::URL_ARTIST.$url);
        $links = $crawler->filter("a.page-link")->links();
        $link = $links[$linkNumber];
        $client->click($link);
        $this->assertSelectorTextContains('h5', $result);
    }
}


