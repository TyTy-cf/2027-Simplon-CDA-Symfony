<?php

namespace App\Tests\Controller\Admin;

use App\Tests\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\TestWith;

class ArtistControllerTest extends AbstractWebTestCase
{
    #[TestWith(['admin@admin.fr', 200])]
    #[TestWith(['', 302])]
    #[TestWith(['kelly.cassin@example.com', 403])]
    public function testAccessDashboardAlbum(string $email, int $code): void
    {
        $client = static::createClient();
        if ('' !== $email) {
            $this->loginUser($client, $email);
        }
        $client->request('GET', self::URL_ADMIN_ARTISTE);
        $this->assertResponseStatusCodeSame($code);
        if (302 === $code) {
            $this->assertResponseRedirects(self::URL_LOGIN);
        }
    }
}
