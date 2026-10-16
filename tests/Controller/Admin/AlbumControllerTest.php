<?php

namespace App\Tests\Controller\Admin;

use App\Repository\AlbumRepository;
use App\Tests\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\TestWith;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AlbumControllerTest extends AbstractWebTestCase
{

    #[TestWith(['PIZZA3000','2026-10-16T13:54:55Z','39',"EP","oskour.jpg",302])]
    public function testFormSubmit(
        string $title,
        string $date,
        string $artist,
        string $type,
        string $poster,
        int $code
    ):void {
        $client = static::createClient();
        $this->loginUser($client, 'admin@admin.fr');
        $crawler = $client->request('GET', self::URL_ADMIN_ALBUM_NEW);

        $image = new UploadedFile(
            'public/uploads/test.jpg',
            'test.jpg',
            'image/jpeg',
            1949
        );

        $form = $crawler->selectButton("Ajouter")->form();
        $form['album[title]'] = $title;
        $form['album[releasedAt]'] = $date;
        $form['album[artist]']->select($artist);
        $form['album[type]']->select($type);
        $form['album[poster]']->upload($image);
        $client->submit($form);

        $this->assertResponseStatusCodeSame($code);
        $albumRepo = $this->getRepository(AlbumRepository::class);
        $album = $albumRepo->findOneBy(['title'=>$title]);
        if($code === 302) {
            $this->removeEntity($album);
        }
    }
}
