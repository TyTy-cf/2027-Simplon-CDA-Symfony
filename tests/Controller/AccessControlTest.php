<?php

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\TestWith;

class AccessControlTest extends AbstractWebTestCase
{

    #[TestWith(['/playlistCreate'])]
    #[TestWith(['/favorite/1'])]
    #[TestWith(['/history/1'])]
    public function testAnonymousIsRedirectedToLogin(string $url): void
    {
        $client = static::createClient();
        $client->request('GET', $url);

        $this->assertResponseRedirects(self::URL_LOGIN);
    }

    #[TestWith(['/admin/album/109'])]
    #[TestWith(['/admin/album/new'])]
    #[TestWith(['/admin/album/109/edit'])]
    #[TestWith(['/admin/artiste/39'])]
    #[TestWith(['/admin/artiste/new'])]
    #[TestWith(['/admin/artiste/39/edit'])]
    public function testAnonymousIsRedirectedToLoginOnAdminRoutes(string $url): void
    {
        $client = static::createClient();
        $client->request('GET', $url);

        $this->assertResponseRedirects(self::URL_LOGIN);
    }

}