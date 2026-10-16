<?php

namespace App\Tests\Controller\Admin;

use App\Repository\ArtistRepository;
use App\Tests\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\TestWith;

class ArtistControllerTest extends AbstractWebTestCase
{

    #[TestWith(['input[name="artist[stageName]"]'])]
    #[TestWith(['textarea[name="artist[biography]"]'])]
    #[TestWith(['input[name="artist[country]"]'])]
    #[TestWith(['button[type="submit"]'])]
    /* ON VERIFIE LA BONNE CORRESPONDANCE DES CHAMPS */
    public function testFields(string $selector): void
    {
        $client = static::createClient();
        $this->loginUser($client, 'admin@admin.fr');
        $client->request('GET', self::URL_ADMIN_ARTIST . '/new');

        $this->assertSelectorExists($selector);
    }
    /* TEST DU SUBMIT */
    public function testSubmitOK(): void
    {
        $client = static::createClient();
        $this->loginUser($client, 'admin@admin.fr');

        $this->DataSubmit($client, self::URL_ADMIN_ARTIST . '/new', 'Ajouter', [
            'artist[stageName]' => 'Artiste Test',
            'artist[biography]' => 'Biographie test',
            'artist[country]' => 'FR',
        ]);

        $this->assertResponseRedirects(self::URL_ADMIN_ARTIST);

        $artist = $this->getRepository(ArtistRepository::class)->findOneBy(['stageName' => 'Artiste Test']);
        $this->assertNotNull($artist);

        $this->removeEntity($artist);
    }

    #[TestWith(['', 'Biographie test', 'FR', 'Le nom de scène doit avoir une valeur'])]
    #[TestWith(['Artiste Test', 'Biographie test', '', 'Le pays doit avoir une valeur'])]
    #[TestWith(['Artiste Test', '', 'FR', 'La biographie doit être renseignée'])]
    /* TEST DE L'ÉCHEC DU SUBMIT */
    public function testSubmitKO(string $stageName, string $biography, string $country, string $message): void
    {
        $client = static::createClient();
        $this->loginUser($client, 'admin@admin.fr');

        $this->DataSubmit($client, self::URL_ADMIN_ARTIST . '/new', 'Ajouter', [
            'artist[stageName]' => $stageName,
            'artist[biography]' => $biography,
            'artist[country]' => $country,
        ]);

        $this->assertSelectorTextContains('body', $message);
        $this->assertNull($this->getRepository(ArtistRepository::class)->findOneBy(['stageName' => 'Artiste Test']));
    }

}