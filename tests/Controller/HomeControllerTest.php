<?php

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;

class HomeControllerTest extends AbstractWebTestCase
{

    public function testAccessOK(): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL_HOME);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Les dernières sorties');
    }

}
