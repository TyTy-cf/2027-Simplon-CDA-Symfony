<?php

namespace App\Tests\Repository;

use App\Repository\AlbumRepository;
use App\Tests\AbstractKernelTestCase;
use PHPUnit\Framework\Attributes\TestWith;


class AlbumRepositoryTest extends AbstractKernelTestCase
{
    #[TestWith(['ep',12,'EP'])]
    #[TestWith(['single',1,'single'])]
    #[TestWith(['album',30,'album'])]
    public function testFindWithRelations(string $type, int $number, string $albumType):void{
        $repo = $this->getRepository(AlbumRepository::class);
        $albums = $repo->findWithRelations($type, $number);
        $this->assertEquals(count($albums),$number);
        $this->assertEquals($albums[0]->getType(),$albumType);
    }

    #[TestWith(['r', 47])]
    #[TestWith(['Ride on time', 1])]
    #[TestWith(['Ride on timeADPOKLZD', 0])]
    public function testFindByTitleLike(string $search, int $number){
        $repo = $this->getRepository(AlbumRepository::class);
        $albums = $repo->findByTitleLike($search);
        $this->assertEquals(count($albums),$number);
    }
}
