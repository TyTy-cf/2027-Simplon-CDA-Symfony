<?php

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\TestWith;

class FavoriteControllerTest extends AbstractWebTestCase
{

    #[TestWith(['kelly.cassin@example.com', true], 'Test with ROLE_USER')]
    #[TestWith(['admin@admin.fr', true], 'Test with ROLE_ADMIN')]
    #[TestWith(['', false], 'Test without logged user')]
    public function testAccess(string $email, bool $expected): void
    {
        $client = static::createClient();
        $this->loginUser($client, $email);

        $client->request('GET', self::URL_FAVORITE);

        if ($expected === true) {
            $this->assertResponseIsSuccessful();
            $this->assertSelectorTextContains('h1', 'Mes favoris');
        } else {
            $this->assertResponseRedirects(self::URL_LOGIN);
        }
    }

}
