<?php

namespace App\Tests\Repository;
use App\Entity\Album;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\TestWith;


use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AlbumRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $em;
    protected function setUp(): void{
        self::bootKernel();
        $this->em= static::getContainer()->get(EntityManagerInterface::class);
    }


    #[TestWith(['ri', true])]
    #[TestWith(['toto', false])]
    public function testAlbumFindLike(string $albumTitle, bool $isTestOk): void
    {
        $albums= $this->em->getRepository(Album::class)->findLike($albumTitle);
        //dump($albums); albums which contain "%$albumTitle%" case insensitive
        
        if ($isTestOk) {
            $this->assertNotEmpty($albums);
        } else {
           $this->assertEmpty($albums);
        }
    }
}
