<?php

namespace App\Tests;

use App\Repository\UserRepository;
use PHPUnit\Framework\Attributes\TestWith;

class RegistrationControllerTest extends AbstractWebTestCase
{
    public function testRegisterPageForm(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::URL_REGISTER);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('input[name="registration_form[email]"]');
        $this->assertSelectorExists('input[name="registration_form[plainPassword][first]"]');
        $this->assertSelectorExists('input[name="registration_form[plainPassword][second]"]');
        $this->assertSelectorExists('input[name="registration_form[agreeTerms]"]');
        $this->assertSelectorTextContains('button[type="submit"]', "S'inscrire");
    }
     
        #[TestWith(['toto@toto.com', '123', '123', self::URL_HOME])]
        //#[TestWith(['', '123', '123', 500])]
        public function testFormSubmit(
            string $email,
            string $password1,
            string $password2,
            string $path

        ): void{
    
        $client = static::createClient();
        // $crawler => DOM / Page HTML
        $crawler = $client->request('GET', self::URL_REGISTER);

        $form = $crawler->selectButton("S'inscrire")->form();

        
        
        $form['registration_form[email]'] = $email;
        $form['registration_form[plainPassword][first]'] = $password1;
        $form['registration_form[plainPassword][second]'] = $password2;
        $form['registration_form[agreeTerms]']->tick();
        $client->submit($form);

        $this->assertResponseRedirects($path);

        if($path === self::URL_REGISTER) {
            // Nécessaire en cas de redirection SI on veut tester l'état du DOM post-redirection
            $client->followRedirect();
            $this->assertSelectorTextContains('div.alert.alert-danger', 'Invalid credentials.');
        }
        $userRepo = $this->getRepository(UserRepository::class);
        $user = $userRepo->findOneBy(['email' => $email]);

         if($user !== null) {
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $entityManager->remove($user);
        $entityManager->flush();
        }
}
}
