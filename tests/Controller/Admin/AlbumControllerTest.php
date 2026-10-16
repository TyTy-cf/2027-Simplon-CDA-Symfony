<?php

namespace App\Tests\Controller\Admin;

use App\Repository\AlbumRepository;
use App\Tests\AbstractWebTestCase;
use DateTime;
use PHPUnit\Framework\Attributes\TestWith;

class AlbumControllerTest extends AbstractWebTestCase
{
    #[TestWith(['admin@admin.fr'])]
    public function testAccessOk(string $email): void
    {
        $client = static::createClient();
        $this->loginUser($client, $email);
        $client->request('GET', self::URL_ADMIN_ALBUM);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Ajouter un album');
    }

    #[TestWith(['kelly.cassin@example.com'])]
    public function testAccessKO(string $email): void
    {
        $client = static::createClient();
        $this->loginUser($client, $email);
        $client->request('GET', self::URL_ADMIN_ALBUM);

        $this->assertResponseStatusCodeSame(403);
    }

    #[TestWith(['admin@admin.fr'])]
    public function testFormExists(string $email): void
    {
        $client = static::createClient();
        $this->loginUser($client, $email);
        $client->request('GET', self::URL_ADMIN_ALBUM);

        $this->assertSelectorExists('input[name="album[title]"]');
        $this->assertSelectorExists('input[name="album[releasedAt]"]');
        $this->assertSelectorExists('select[name="album[artist]"]');
        $this->assertSelectorExists('select[name="album[type]"]');
        $this->assertSelectorExists('input[name="album[poster]"]');
        $this->assertSelectorTextContains('button[type="submit"]', 'Ajouter');
    }

    //Souci avec code d'erreur et date vide
    #[TestWith(['admin@admin.fr', 'Halo', '2026-10-15T12:14:00Z', '75', 'EP', '1.jpg', 302])]
    #[TestWith(['admin@admin.fr', '', '2026-10-15T12:14:00Z', '75', 'EP', '1.jpg', 200])]
    #[TestWith(['admin@admin.fr', 'Halo', '', '75', 'EP', '1.jpg', 200])]
    #[TestWith(['admin@admin.fr', 'Halo', '2026-10-15T12:14:00Z', '75', 'EP', '', 200])]
    public function testFormSubmit(
        string $email,
        string $title,
        string $date,
        string $artist,
        string $type,
        string $poster,
        int $code,
    ): void {

        $client = static::createClient();
        $this->loginUser($client, $email);
        $crawler = $client->request('GET', self::URL_ADMIN_ALBUM);

        $form = $crawler->selectButton('Ajouter')->form();

        $form['album[title]'] = $title;
        $form['album[releasedAt]'] = $date;
        $form['album[artist]']->select($artist);
        $form['album[type]']->select($type);
        $form['album[poster]']->upload($poster);

        $client->submit($form);

        $this->assertResponseStatusCodeSame($code);

        $albumRepo = $this->getRepository(AlbumRepository::class);
        $album = $albumRepo->findOneBy(['title' => $title]);

        if ($code === 302) {
            $this->removeEntity($album);
        }
    }


}
