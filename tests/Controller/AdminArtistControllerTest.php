<?php

namespace App\Tests\Controller;

use App\Repository\AlbumRepository;
use App\Repository\ArtistRepository;
use App\Tests\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\TestWith;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminArtistControllerTest extends AbstractWebTestCase
{
    #[TestWith(['admin@admin.fr', 200])]
    #[TestWith(['lynch.dora@example.com', 403])]
    public function testAccessOk(string $email, int $codeHttp)
    {
        $client = static::createClient();
        $this->loginUser($client, $email);
        $client->request('GET', self::URL_ARTIST_ADMIN);

        $this->assertResponseStatusCodeSame($codeHttp);
    }

    #[TestWith(['admin@admin.fr'])]
    public function testFormExists(string $email): void
    {
        $client = static::createClient();
        $this->loginUser($client, $email);
        $client->request('GET', self::URL_ARTIST_ADMIN . '/new');

        $this->assertSelectorExists('input[name="artist[stageName]"]');
        $this->assertSelectorExists('input[name="artist[country]"]');
        $this->assertSelectorExists('textarea[name="artist[biography]"]');
        $this->assertSelectorTextContains('button[type="submit"]', "Ajouter");
    }
    #[TestWith(['admin@admin.fr', 'JJG', 'France', 'biographie', 302])]
    public function testFormSubmit(
        string $email,
        string $stageName,
        string $country,
        string $biography,
        int $code
    ): void {
        $client = static::createClient();
        $this->loginUser($client, $email);

        // $crawler => DOM / Page HTML
        $crawler = $client->request('GET', self::URL_ARTIST_ADMIN . '/new');

        $form = $crawler->selectButton("Ajouter")->form();

        $form['artist[stageName]'] = $stageName;
        $form['artist[country]'] = $country;
        $form['artist[biography]'] = $biography;

        $client->submit($form);

        $this->assertResponseStatusCodeSame($code);

        $artistRepo = $this->getRepository(ArtistRepository::class);
        $artist = $artistRepo->findOneBy(['stageName' => $stageName]);
        if ($artist !== null && $code === 302) {
            $em = $this->getContainer()->get('doctrine.orm.entity_manager');
            $em->remove($artist);
            $em->flush();
        }
    }
}
