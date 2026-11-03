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

    #[TestWith([1])]
    #[TestWith([2])]
    #[TestWith([3])]
    public function testArtistsPagination(int $page): void
    {
        $client = static::createClient();

        $client->request('GET', self::URL_ARTIST . "?page=" . $page);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorCount(5, '.card-title');
    }
}
