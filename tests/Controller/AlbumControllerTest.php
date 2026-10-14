<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\Attributes\TestWith;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AlbumControllerTest extends WebTestCase
{

    #[TestWith([1, 'S16 - 00:07:42', 'single'])]
    #[TestWith([103, 'Tempora saepe cumque. - 00:02:52', 'EP'])]
    #[TestWith([108, 'Sit quisquam explicabo. - 00:00:00', 'album'])]
    public function testAccessOK(int $albumId, string $title, string $type): void
    {
        $client = static::createClient();
        $client->request('GET', '/album/' . $albumId);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1.card-title', $title);
        $this->assertSelectorTextContains('span.badge', $type);
    }

}
