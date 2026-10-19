<?php

namespace App\Tests\Controller\Admin;

use App\Repository\AlbumRepository;
use App\Tests\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\TestWith;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AlbumControllerTest extends AbstractWebTestCase
{
    #[TestWith(['admin@admin.fr',200])]
    #[TestWith(['', 302])]
    #[TestWith(['kelly.cassin@example.com',403])]
    public function testAccessDashboardAlbum(string $email, int $code):void{
        $client = static::createClient();        
        if($email!==""){
            $this->loginUser($client, $email);
        }
        $client->request('GET', self::URL_ADMIN_ALBUM);
        $this->assertResponseStatusCodeSame($code);
        if($code === 302){
            $this->assertResponseRedirects(self::URL_LOGIN);
        }
    }

    #[TestWith(['admin@admin.fr',200])]
    #[TestWith(['', 302])]
    #[TestWith(['kelly.cassin@example.com',403])]
    public function testAccessFormAddAlbum(string $email, int $code):void{
        $client = static::createClient();        
        if($email!==""){
            $this->loginUser($client, $email);
        }
        $client->request('GET', self::URL_ADMIN_ALBUM_NEW);
        $this->assertResponseStatusCodeSame($code);
        if($code === 302){
            $this->assertResponseRedirects(self::URL_LOGIN);
        }
    }

    public function testFormAddAlbumExist():void{
        $client = static::createClient();
        $this->loginUser($client, 'admin@admin.fr');
        $client->request('GET', self::URL_ADMIN_ALBUM_NEW);

        $this->assertSelectorExists('input[name="album[title]"]');
        $this->assertSelectorExists('input[name="album[releasedAt]"]');
        $this->assertSelectorExists('select[name="album[artist]"]');
        $this->assertSelectorExists('select[name="album[type]"]');
        $this->assertSelectorExists('input[name="album[poster]"]');
        $this->assertSelectorExists('button[name="album[submit]"]');
    }

    #[TestWith(['PIZZA3000','2026-10-16T13:54:55Z','39',"EP",true,302])]
    #[TestWith(['','2026-10-16T13:54:55Z','39',"EP",true,500])]
    #[TestWith(['PIZZA3000','','39',"EP",true,500])]
    #[TestWith(['PIZZA3000','2026-10-16T13:54:55Z','39',"EP",false,500])]
    public function testFormSubmit(string $title, string $date, string $artist, string $type, bool $hasPoster, int $code):void{
        $client = static::createClient();
        $this->loginUser($client, 'admin@admin.fr');
        $crawler = $client->request('GET', self::URL_ADMIN_ALBUM_NEW);
        $form = $crawler->selectButton("Ajouter")->form();

        if($hasPoster){
            $image = new UploadedFile(
            // Path to the file to send
            'public/uploads/1.jpg',
            // Name of the sent file
            '1.jpg',
            // MIME type
            'image/jpeg',
            // Size of the file
            9988
            );
            $form['album[poster]']->upload($image);
        }
        

        $form['album[title]'] = $title;
        $form['album[releasedAt]'] = $date;
        $form['album[artist]']->select($artist);
        $form['album[type]']->select($type);
        
        $client->submit($form);

        $this->assertResponseStatusCodeSame($code);
        $albumRepo = $this->getRepository(AlbumRepository::class);
        $album = $albumRepo->findOneBy(['title'=>$title]);
        if($code === 302){
            $this->removeEntity($album);
        }
    }
}

//The selected choice is invalid.
// pour artiste et type
