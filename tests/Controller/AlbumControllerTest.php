<?php

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\TestWith;

class AlbumControllerTest extends AbstractWebTestCase
{


    #[TestWith([6, 'The Essentials - 01:56:08', 'single'])]
    #[TestWith([9, 'Beatae hic. - 01:02:22', 'EP'])]
    #[TestWith([7, 'Radio - 01:23:27', 'album'])]
    public function testAccessOK(int $albumId, string $title, string $type): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL_ALBUM . '/' . $albumId);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1.card-title', $title);
        $this->assertSelectorTextContains('span.badge', $type);
    }

}
