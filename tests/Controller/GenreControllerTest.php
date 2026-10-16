<?php

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;

class GenreControllerTest extends AbstractWebTestCase
{
    public function testAccessOK(): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL_GENRE);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Blues');
    }
}
