<?php

namespace App\Tests\Templates;

use App\Tests\AbstractWebTestCase;

class NavBarTest extends AbstractWebTestCase
{
    public function testNavBarGenreOrdered(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $crawlerLi = $crawler->filter('li > a.dropdown-item');
        $sorted = true;
        $temp = $crawlerLi->getNode(0)->nodeValue;
        foreach($crawlerLi as $node){
            if($node->nodeValue<$temp){
                $sorted = false;
                break;
            }
            $temp = $node->nodeValue;
        }
        $this->assertEquals($sorted, true);
    }
}
