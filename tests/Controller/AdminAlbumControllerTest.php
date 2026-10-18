<?php

namespace App\Tests\Controller;

use App\Repository\AlbumRepository;
use App\Tests\AbstractWebTestCase;
use DateTimeInterface;
use PHPUnit\Framework\Attributes\TestWith;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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

    #[TestWith(['admin@admin.fr', 'title', '2026-06-01 10:30:00', 'Woodkid', 'EP', '/../../public/uploads/2.jpg', 302])]
    public function testFormSubmit(
        string $email,
        string $title,
        string $date,
        string $artist,
        string $type,
        string $file,
        int $code
    ): void {
        $client = static::createClient();
        $this->loginUser($client, $email);

        // $crawler => DOM / Page HTML
        $crawler = $client->request('GET', self::URL_ALBUM_ADMIN . '/new');

        $form = $crawler->selectButton("Ajouter")->form();
        $absolutePath = __DIR__ . $file;

        $uploadedFile = new UploadedFile(
            $absolutePath,
            '2.jpg',
            'image/jpeg',
            null,
            true // Mode test (évite de bouger le vrai fichier)
        );

        $form['album[title]'] = $title;
        $form['album[releasedAt]'] = $date;
        $form['album[artist]']->$artist;
        $form['album[type]']->$type;
        $form['album[poster]'] = $uploadedFile;

        $client->submit($form);

        $this->assertResponseStatusCodeSame($code);

        $albumRepo = $this->getRepository(AlbumRepository::class);
        $album = $albumRepo->findOneBy(['title' => $title]);
        if ($album !== null && $code === 302) {
            $em = $this->getContainer()->get('doctrine.orm.entity_manager');
            $em->remove($album);
            $em->flush();
        }
    }
}
