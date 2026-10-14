<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use App\Tests\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\TestWith;

class RegistrationControllerTest extends AbstractWebTestCase
{
    public function testFormSignInExist() : void
    {   
        $client = static::createClient();
        $client->request('GET', self::URL_REGISTER);

        $this->assertSelectorExists('input[name="registration_form[email]"]');
        $this->assertSelectorExists('input[name="registration_form[agreeTerms]"]');
        $this->assertSelectorExists('input[name="registration_form[plainPassword][first]"]');
        $this->assertSelectorExists('input[name="registration_form[plainPassword][second]"]');
    }


    #[TestWith(['oskour@oskour.fr','oskour','oskour',302])]
    #[TestWith(['oskour@oskour.fr','','',422])]
    #[TestWith(['oskour@oskour.fr','oskour','',422])]
    #[TestWith(['oskour@oskour.fr','oskour','jpp',422])]
    public function testFormSubmit(
        string $email,
        string $password1,
        string $password2,
        int  $code
    ): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::URL_REGISTER);
        $form = $crawler->selectButton("S'inscrire")->form();
        $form['registration_form[email]'] = $email;
        $form['registration_form[plainPassword][first]'] = $password1;
        $form['registration_form[plainPassword][second]'] = $password2;
        $form['registration_form[agreeTerms]']->tick();
        $client->submit($form);

        $this->assertResponseStatusCodeSame($code);

        if ($code === 422 && $password2!="" && $password1!="" && $email!=null) {
            $this->assertSelectorTextContains('div.invalid-feedback.d-block', 'Les deux champs doivent être identiques.');
        }

        if ($code === 422 && $password1 === "" && $email!=null) {
            $this->assertSelectorTextContains('div.invalid-feedback.d-block', 'This value should not be blank.');
        }

        if ($code === 422 && $email === "") {
            $this->assertSelectorTextContains('div.invalid-feedback.d-block', "L'email doit avoir une valeur");
        }

        $userRepo = $this->getRepository(UserRepository::class);
        $user = $userRepo->findOneBy(["email"=>$email]);
        if($user != null){
            $em = $this->getContainer()->get('doctrine.orm.entity_manager');
            $em->remove($user);
            $em->flush();
        }
    }
}
