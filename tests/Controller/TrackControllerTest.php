<?php

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;

class TrackControllerTest extends AbstractWebTestCase
{
    public function testAccessOK(): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL_TRACK);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Buckridge Willie');
    }
}
