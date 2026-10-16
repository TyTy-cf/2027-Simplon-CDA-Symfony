<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use App\Tests\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\TestWith;

class RegistrationControllerTest extends AbstractWebTestCase
{

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
        $this->assertSelectorExists('input[name="registration_form[agreeTerms]"]');
        $this->assertSelectorExists('input[name="registration_form[plainPassword][first]"]');
        $this->assertSelectorExists('input[name="registration_form[plainPassword][second]"]');
        $this->assertSelectorTextContains('button[type="submit"]', 'S\'inscrire');
    }

    #[TestWith(['toto@zero.fr', '123', '123', 302, null])]
    #[TestWith(['', '123', '123', 422, 'L\'email doit être rempli'])]
    #[TestWith(['toto@zero.fr', '123', '', 422, 'Les deux champs doivent être identiques.'])]
    #[TestWith(['toto@zero.fr', '', '123', 422, 'Les deux champs doivent être identiques.'])]
    #[TestWith(['toto@zero.fr', '', '', 422, 'Le mot de passe doit être rempli'])]
    #[TestWith(['toto@zero.fr', '123', '123123', 422, 'Les deux champs doivent être identiques.'])]
    #[TestWith(['totozero.fr', '123', '123', 422, 'L\'email doit être valide'])]
    #[TestWith(['kelly.cassin@example.com', '123', '123', 422, 'There is already an account with this email'])]
    public function testFormSubmit(
        string $email, string $password1, string $password2, int $code, ?string $msg
    ): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::URL_REGISTER);

        $form = $crawler->selectButton('S\'inscrire')->form();
        $form['registration_form[email]'] = $email;
        $form['registration_form[plainPassword][first]'] = $password1;
        $form['registration_form[plainPassword][second]'] = $password2;
        $form['registration_form[agreeTerms]']->tick();
        $client->submit($form);

        $this->assertResponseStatusCodeSame($code);

        if ($msg !== null) {
            $this->assertSelectorTextContains('div.invalid-feedback', $msg);
        }

        $userRepo = $this->getRepository(UserRepository::class);
        $user = $userRepo->findOneBy(['email' => $email]);
        if ($code === 302) {
            $this->removeEntity($user);
        }
    }

}
