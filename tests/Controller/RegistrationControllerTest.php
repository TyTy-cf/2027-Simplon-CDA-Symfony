<?php

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;
use App\Repository\UserRepository;
use PHPUnit\Framework\Attributes\TestWith;



class RegistrationControllerTest extends AbstractWebTestCase
{
    public function testAccessOK(): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL_REGISTER);

        $this->assertResponseIsSuccessful();
    }


    public function testFormExists()
    {
        $client = static::createClient();
        $client->request('GET', self::URL_REGISTER);

        $this->assertSelectorExists('input[name="registration_form[email]"]');
        $this->assertSelectorExists('input[name="registration_form[agreeTerms]"]');
        $this->assertSelectorExists('input[name="registration_form[plainPassword][first]"]');
        $this->assertSelectorExists('input[name="registration_form[plainPassword][second]"]');

        $this->assertSelectorTextContains('button[type="submit"]', 'S\'inscrire');

    }



    #[TestWith(['toto@gmail.com', '123', '123', 302])]
    #[TestWith(['toto@gmail.com', '', '123', 422])]
    #[TestWith(['toto@gmail.com', '123', '', 422])]
    #[TestWith(['', '123', '123', 422])]
    #[TestWith(['toto@gmail.com', '123', '1233', 422])]
    #[TestWith(['kelly.cassin@example.com', '123', '123', 422])]
    public function testFormSubmit(string $email, string $password1, string $password2, int $code)
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::URL_REGISTER);

        $form = $crawler->selectButton('S\'inscrire')->form();
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
