<?php

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;

class ArtistControllerTest extends AbstractWebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL_ARTIST);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Artistes');
    }
}
