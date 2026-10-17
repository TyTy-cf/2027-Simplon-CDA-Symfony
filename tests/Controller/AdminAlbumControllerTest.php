<?php

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\TestWith;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminAlbumControllerTest extends AbstractWebTestCase
{
    #[TestWith(['admin@admin.fr', 200])]
    #[TestWith(['lynch.dora@example.com', 403])]
    public function testAccessOk(string $email, int $codeHttp)
    {
        $client = static::createClient();
        $this->loginUser($client, $email);
        $client->request('GET', self::URL_ALBUM_ADMIN);

        $this->assertResponseStatusCodeSame($codeHttp);
    }

    #[TestWith(['admin@admin.fr'])]
    public function testFormExists(string $email): void
    {
        $client = static::createClient();
        $this->loginUser($client, $email);
        $client->request('GET', self::URL_ALBUM_ADMIN . '/new');

        $this->assertSelectorExists('input[name="album[title]"]');
        $this->assertSelectorExists('input[name="album[releasedAt]"]');
        $this->assertSelectorExists('select[name="album[artist]"]');
        $this->assertSelectorExists('select[name="album[type]"]');
        $this->assertSelectorTextContains('button[type="submit"]', "Ajouter");
    }
}
