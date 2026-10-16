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

    public function testFormExists(): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL_TRACK);

        $this->assertSelectorExists('input[name="track[title]"]');
        $this->assertSelectorExists('input[name="track[duration]"]');
        $this->assertSelectorExists('input[name="track[number]"]');
        $this->assertSelectorExists('button[name="track[submit]"]');
    }
}
