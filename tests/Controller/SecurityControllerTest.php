<?php

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\TestWith;

class SecurityControllerTest extends AbstractWebTestCase
{
    public function testAccessOK(): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL_LOGIN);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Se connecter');
    }

    public function testFormExists(): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL_LOGIN);

        $this->assertSelectorExists('input[name="email"]');
        $this->assertSelectorExists('input[name="password"]');
        $this->assertSelectorExists('input[name="_remember_me"]');
        $this->assertSelectorTextContains('button[type="submit"]', 'Se connecter');
    }

    #[TestWith(['admin@admin.fr', '123', self::URL_HOME],'email correct')]
    #[TestWith(['admin@admin.fr', '', self::URL_LOGIN],'mdp manquant')]
    #[TestWith(['admin@admin.fr', '12345', self::URL_LOGIN],'mauvais mdp')]
    #[TestWith(['admin@toto.fr', '123', self::URL_LOGIN],"mauvais domaine")]
    #[TestWith(['', '123', self::URL_LOGIN],"pas de mail")]
    #[TestWith(['', '', self::URL_LOGIN],'aucune données')]
    public function testFormSubmit(
        string $email,
        string $password,
        string $path
    ): void
    {
        $client = static::createClient();
        // $crawler => DOM / Page HTML
        $crawler = $client->request('GET', self::URL_LOGIN);

        // /!\ Récupérer le form via le button, fonctionne uniquement
        // si le button est bien enfant de la balise form /!\
        $form = $crawler->selectButton('Se connecter')->form();
        // Cas alternatif :
//        $form = $crawler->filter('form[name="form[login]"]')->form();
        $form['email'] = $email;
        $form['password'] = $password;
        $client->submit($form);

        $this->assertResponseRedirects($path);

        if ($path === self::URL_LOGIN) {
            // Nécessaire en cas de redirection SI on veut tester l'état du DOM post-redirection
            $client->followRedirect();
            $this->assertSelectorTextContains('div.alert.alert-danger', 'Invalid credentials.');
        }
    }

    public function testLogoutExistsAsLoggedUser(): void
    {
        $client = static::createClient();
        $this->loginUser($client, 'kelly.cassin@example.com');
        $client->request('GET', self::URL_LOGIN);

        $this->assertSelectorExists('a[href="/logout"]');
    }
}
