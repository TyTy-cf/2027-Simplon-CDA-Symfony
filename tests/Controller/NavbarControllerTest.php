<?php

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\TestWith;

class NavbarControllerTest extends AbstractWebTestCase
{
    public function testFindNavbar(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('a', 'Harmonia');
    }

    #[TestWith(['Artistes'])]
    #[TestWith(['Se connecter'])]
    #[TestWith(['S\'inscrire'])]
    public function testTitles(string $expected): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $found = false;
        $crawlerTitle = $crawler->filter('a.nav-link');
        foreach ($crawlerTitle as $title) {
            if ($title->textContent === $expected) {
                $found = true;
                break;
            }
        }

        $this->assertTrue($found);
    }

    #[TestWith(['kelly.cassin@example.com', 'Profil'])]
    #[TestWith(['kelly.cassin@example.com', 'Favoris'])]
    public function testTitlesUser(string $email, string $expected): void
    {
        $client = static::createClient();
        $this->loginUser($client, $email);
        $crawler = $client->request('GET', '/');

        $found = false;
        $crawlerTitle = $crawler->filter('a.nav-link');
        foreach ($crawlerTitle as $title) {
            if ($title->textContent === $expected) {
                $found = true;
                break;
            }
        }

        $this->assertTrue($found);
    }

    #[TestWith(['admin@admin.fr', 'Admin'])]
    #[TestWith(['admin@admin.fr', 'Se déconnecter'])]
    public function testTitlesAdmin(string $email, string $expected): void
    {
        $client = static::createClient();
        $this->loginUser($client, $email);
        $crawler = $client->request('GET', '/');

        $found = false;
        $crawlerTitle = $crawler->filter('a.nav-link');
        foreach ($crawlerTitle as $title) {
            if ($title->textContent === $expected) {
                $found = true;
                break;
            }
        }

        $this->assertTrue($found);
    }


}
