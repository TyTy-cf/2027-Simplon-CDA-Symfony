<?php

namespace App\Tests\Repository;
use App\Repository\GenreRepository;
use App\Entity\Genre;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\TestWith;


use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GenreRepositoryTest extends KernelTestCase
{

    private EntityManagerInterface $em;
    protected function setUp(): void{
        self::bootKernel();
        $this->em= static::getContainer()->get(EntityManagerInterface::class);
    }

    #[TestWith(['Pop', true])]
    #[TestWith(['toto', false])]
    public function testGenre(string $genreName, bool $isTestOk): void
    {
        

        $genres= $this->em->getRepository(Genre::class)->findByNameLike($genreName);
        //dump($genres); array of genre which caontains $genreName

        if ($isTestOk) {
            $this->assertNotEmpty($genres);
        } else {
           $this->assertEmpty($genres); 
        }
        

        
    }
}
