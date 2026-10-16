<?php

namespace App\Tests;

use PHPUnit\Framework\Attributes\TestWith;

class RegisterControllerTest extends AbstractWebTestCase
{
    // #[TestWith(['GET', self::URL_REGISTER])]
    public function testAccessOK(): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL_REGISTER);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Inscription');
    }
    public function testFormExists(): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL_REGISTER);

        $this->assertSelectorExists('input[name="registration_form[email]"]');
        $this->assertSelectorExists('input[name="registration_form[plainPassword][first]"]');
        $this->assertSelectorExists('input[name="registration_form[plainPassword][second]"]');
        $this->assertSelectorTextContains('button[type="submit"]', 'S\'inscrire');
    }

    // #[TestWith(['admin@admin.fr', '123', self::URL_HOME])]
    // #[TestWith(['admin@admin.fr', '', self::URL_LOGIN])]
    // #[TestWith(['admin@admin.fr', '12345', self::URL_LOGIN])]
    // #[TestWith(['admin@toto.fr', '123', self::URL_LOGIN])]
    // #[TestWith(['', '123', self::URL_LOGIN])]
    #[TestWith(['t@toto.fr', '123', '123', self::URL_HOME])]
    public function testFormSubmit(
        string $email,
        string $password,
        string $confirmPassword,
        string $path
    ): void {
        $client = static::createClient();
        $crawler = $client->request('GET', self::URL_REGISTER);

        // /!\ Récupérer le form via le button, fonctionne uniquement
        // si le button est bien enfant de la balise form /!\
        $form = $crawler->selectButton('S\'inscrire')->form();
        // Cas alternatif :
        //        $form = $crawler->filter('form[name="form[login]"]')->form();
        $form['registration_form[email]'] = $email;
        $form['registration_form[plainPassword][first]'] = $password;
        $form['registration_form[plainPassword][second]'] = $confirmPassword;
        $form['registration_form[agreeTerms]']->tick();
        $client->submit($form);

        $this->assertResponseRedirects($path);

        if ($path === self::URL_REGISTER) {
            // Nécessaire en cas de redirection SI on veut tester l'état du DOM post-redirection
            $client->followRedirect();
            $this->assertSelectorTextContains('div.alert.alert-danger', 'Invalid credentials.');
        }
    }
    // $userRepo = $this->getRepository(UserRepository::class);
    // $user = $userRepo->findOneBy(['email' => $email]);

    // if($user !== null) {
    // $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
    // $entityManager->remove($user);
    // $entityManager->flush();
    // }
}
