<?php

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\TestWith;

class AlbumControllerTest extends AbstractWebTestCase
{


    #[TestWith([109, 'S16 - 01:29:43', 'single'])]
    #[TestWith([206, 'Temporibus quaerat. - 01:24:22', 'EP'])]
    #[TestWith([135, 'Occaecati quas. - 01:05:51', 'album'])]
    #[TestWith([218, 'Oui oui - 00:00:00', 'single'])]
    public function testAccessOK(int $albumId, string $title, string $type): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL_ALBUM . '/' . $albumId);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1.card-title', $title);
        $this->assertSelectorTextContains('span.badge', $type);
    }

}
