<?php

namespace App\Tests\Templates;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PaginationTest extends WebTestCase
{
    public function testPaginationExist(): void
    {
        $client = static::createClient();
        $client->request('GET', '/artiste');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('ul.pagination > li > span', 'Previous');
    }

    public function testPaginationLinkCount(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/artiste');
        $paginationItems = $crawler->filter("ul.pagination > li.page-item");
        $paginationLinks = $crawler->filter("ul.pagination > li.page-item > a");

        $this->assertCount(9,$paginationItems);
        $this->assertCount(6,$paginationLinks);

    }

    public function testFirstPageIsPageOne()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/artiste');
        $this->assertSelectorTextContains('ul.pagination > li.active > span', '1');
    }

    public function testPageTwoIsPageTwo()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/artiste?page=2');
        $this->assertSelectorTextContains('ul.pagination > li.active > span', '2');
    }
}
