<?php

namespace App\Tests\Controller;

use App\Repository\AlbumRepository;
use App\Tests\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\TestWith;
use App\Repository\ArtistRepository;

use Symfony\Component\HttpFoundation\File\UploadedFile;

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

    public function testAddAlbumPageExist(): void
    {

        $client = static::createClient();
        $this->loginUser($client, 'admin@admin.fr');

        $client->request('GET', self::URL_ADMIN_ADD_ALBUM);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Ajouter un album');
    }

    public function testFormExist(): void
    {

        $client = static::createClient();
        $this->loginUser($client, 'admin@admin.fr');
        $client->request('GET', self::URL_ADMIN_ADD_ALBUM);

        $this->assertSelectorExists('input[name="album[title]"]');
        $this->assertSelectorExists('input[name="album[releasedAt]"]');
        $this->assertSelectorExists('select[name="album[artist]"]');
        $this->assertSelectorExists('select[name="album[type]"]');
        $this->assertSelectorExists('input[name="album[poster]"]');
        $this->assertSelectorTextContains('button[type="submit"]', 'Ajouter');
    }

    #[TestWith(['Blabla', '2026-10-15T12:14', 'Bershy', 'EP', '1.jpg', self::ADMIN_ALBUM])]
    public function testFormSubmit(
        string $title,
        string $date,
        string $artiste,
        string $type,
        string $file,
        string $path
    ): void {

        $poster = new UploadedFile(
            __DIR__ . '/../../public/uploads/' . $file,
            $file,
            'image/jpeg',
            null,
            true
        );

        $client = static::createClient();
        $this->loginUser($client, 'admin@admin.fr');
        $crawler = $client->request('GET', self::URL_ADMIN_ADD_ALBUM);

        $artistRepo = $this->getRepository(ArtistRepository::class);
        $artistObject = $artistRepo->findOneBy(['stageName' => $artiste]);

        $form = $crawler->selectButton('Ajouter')->form();

        $form['album[title]'] = $title;
        $form['album[releasedAt]'] = $date;
        $form['album[artist]'] = $artistObject->getId();
        $form['album[type]'] = $type;
        $form['album[poster]'] = $poster;

        $client->submit($form);

        $this->assertResponseRedirects($path);

        $client->followRedirect();
        $this->assertAnySelectorTextContains('td', $title);

        $albumRepo = $this->getRepository(AlbumRepository::class);
        $album = $albumRepo->findOneBy(['title' => $title]);
        $this->removeEntity($album);
    }
}
