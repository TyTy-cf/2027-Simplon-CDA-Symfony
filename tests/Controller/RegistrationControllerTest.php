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


    #[TestWith(['oskour@oskour.fr','oskour','oskour',302, null])]
    #[TestWith(['oskouroskour.fr','oskour','oskour',422, 'Vous devez saisir un email'])]
    #[TestWith(['oskour@oskour.fr','','',422,'This value should not be blank.'])]
    #[TestWith(['oskour@oskour.fr','oskour','',422,'Les deux champs doivent être identiques.'])]
    #[TestWith(['','oskour','oskour',422,"L'email doit avoir une valeur"])]
    #[TestWith(['oskour@oskour.fr','oskour','jpp',422,'Les deux champs doivent être identiques.'])]
    #[TestWith(['kelly.cassin@example.com','oskour','oskour',422,'There is already an account with this email'])]
    public function testFormSubmit(
        string $email,
        string $password1,
        string $password2,
        int  $code,
        ?string $msg
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

        if($msg !== null){
            $this->assertSelectorTextContains('div.invalid-feedback.d-block', $msg);
        }

        $userRepo = $this->getRepository(UserRepository::class);
        $user = $userRepo->findOneBy(["email"=>$email]);
        if($code === 302){
            $this->removeEntity($user);
        }
    }
}
