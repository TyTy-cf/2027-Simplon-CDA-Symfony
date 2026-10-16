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
        $this->assertSelectorExists('input[name="registration_form[plainPassword][first]"]');
        $this->assertSelectorExists('input[name="registration_form[plainPassword][second]"]');
        $this->assertSelectorExists('input[name="registration_form[agreeTerms]"]');
        $this->assertSelectorTextContains('button[type="submit"]', "S'inscrire");
    }

    #[TestWith(['joe@dalton.fr', '123', '123', 302])]
    #[TestWith(['joe@dalton.fr', '123', '124', 422])]
    #[TestWith(['', '123', '123', 422])]
    #[TestWith(['joe@dalton.fr', '123', '', 422])]
    #[TestWith(['joe@dalton.fr', '', '123', 422])]
    #[TestWith(['joe@dalton.fr', '', '', 422])]
    #[TestWith(['lynch.dora@example.com', '123', '123', 422])]

    public function testFormSubmit(
        string $email,
        string $password1,
        string $password2,
        int $code
    ): void {
        $client = static::createClient();
        // $crawler => DOM / Page HTML
        $crawler = $client->request('GET', self::URL_REGISTER);

        $form = $crawler->selectButton("S'inscrire")->form();

        $form['registration_form[email]'] = $email;
        $form['registration_form[agreeTerms]']->tick();
        $form['registration_form[plainPassword][first]'] = $password1;
        $form['registration_form[plainPassword][second]'] = $password2;

        $client->submit($form);

        $this->assertResponseStatusCodeSame($code);

        $userRepo = $this->getRepository(UserRepository::class);
        $user = $userRepo->findOneBy(['email' => $email]);
        if ($user !== null && $code === 302) {
            $em = $this->getContainer()->get('doctrine.orm.entity_manager');
            $em->remove($user);
            $em->flush();
        }
    }
}
