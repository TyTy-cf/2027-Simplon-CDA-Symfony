<?php

namespace App\Tests\Templates;

use App\Tests\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\TestWith;



class NavbarTest extends AbstractWebTestCase
{
    public function testGenreOrder(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::URL_HOME);

        $crawlersItems = $crawler->filter("li > a.dropdown-item");
        $tabGenre = [];
        foreach ($crawlersItems as $item) {
            array_push($tabGenre, $item->textContent);
        }
        $tabGenreSort = $tabGenre;
        sort($tabGenreSort);
        $this->assertTrue($tabGenre === $tabGenreSort);
    }


    #[TestWith(['admin@admin.fr',true])]
    #[TestWith(['kelly.cassin@example.com',false])]
    #[TestWith(['',false])]
    public function testNavLink(string $email, bool $isAdmin): void
    {
        $client = static::createClient();
        if ($email !== null && $email !== "") {
            $this->loginUser($client, $email);
            $client->request('GET', self::URL_PROFILE);

            $this->assertResponseIsSuccessful();//connecter sur la page profil

            if ($isAdmin) {
                $this->assertAnySelectorTextContains(".nav-link", "Admin");//au moins un des nav link contient Admin
            } else {
                $this->assertAnySelectorTextNotContains(".nav-link", "Admin");//aucun des nav link contient Admin
            }
        } else {
            $client->request('GET', self::URL_PROFILE);
            $this->assertResponseRedirects(self::URL_LOGIN);

        }
        
    }

}
