<?php

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\TestWith;

class TrackControllerTest extends AbstractWebTestCase
{
    public function testAccessOK(): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL_TRACK);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Buckridge Willie');
    }

    public function testFormExists(): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL_TRACK);

        $this->assertSelectorExists('input[name="track[title]"]');
        $this->assertSelectorExists('input[name="track[duration]"]');
        $this->assertSelectorExists('input[name="track[number]"]');
        $this->assertSelectorExists('button[name="track[submit]"]');
    }

    #[TestWith(['Mon premier titre', 215, 1])]
    #[TestWith(['Intro', 62, 2])]
    #[TestWith(['Outro - Remix', 340, 10])]
    public function testAddTrack(string $title, int $duration, int $number): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL_TRACK);
        $this->assertResponseIsSuccessful();

        $client->submitForm('track[submit]', [
            'track[title]' => $title,
            'track[duration]' => $duration,
            'track[number]' => $number,
        ]);

        $this->assertResponseRedirects();
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('body', $title);
    }
}
