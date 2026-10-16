<?php

namespace App\Tests\Controller;

use App\Repository\PlaylistRepository;
use App\Repository\UserRepository;
use App\Tests\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\TestWith;

class PlaylistControllerTest extends AbstractWebTestCase
{

    #[TestWith(['input[name="playlist[name]"]'])]
    #[TestWith(['textarea[name="playlist[description]"]'])]
    #[TestWith(['input[name="playlist[isPublic]"][type="checkbox"]'])]
    #[TestWith(['button[type="submit"]'])]
    /* ON VERIFIE LA BONNE CORRESPONDANCE DES CHAMPS */
    public function testFields(string $selector): void
    {
        $client = static::createClient();
        $this->loginUser($client, 'kelly.cassin@example.com');
        $client->request('GET', self::URL_PLAYLIST_CREATE);

        $this->assertSelectorExists($selector);
    }
    
    /* TEST DU SUBMIT */
    public function testSubmitOK(): void
    {
        $client = static::createClient();
        $this->loginUser($client, 'kelly.cassin@example.com');

        $this->DataSubmit($client, self::URL_PLAYLIST_CREATE, 'Create Playlist', [
            'playlist[name]' => 'Playlist Test',
            'playlist[description]' => 'Description test',
            'playlist[isPublic]' => true,
        ]);

        $user = $this->getRepository(UserRepository::class)->findOneBy(['email' => 'kelly.cassin@example.com']);
        $this->assertResponseRedirects(self::URL_PROFILE . '?id=' . $user->getId());

        $playlist = $this->getRepository(PlaylistRepository::class)->findOneBy(['name' => 'Playlist Test']);
        $this->assertNotNull($playlist);
        $this->assertSame($user->getId(), $playlist->getUser()->getId());

        $this->removeEntity($playlist);
    }

}