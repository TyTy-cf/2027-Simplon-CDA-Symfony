<?php

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use PHPUnit\Framework\Attributes\TestWith;


class GenreControllerTest extends AbstractWebTestCase
{
    #[TestWith([20, 'Electro'])]
    #[TestWith([14, 'Rock'])]
    public function testAccessOk(int $genreId, string $genreName): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL_GENRE . '/' . $genreId);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $genreName);
    }
}
