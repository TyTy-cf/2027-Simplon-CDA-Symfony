<?php

namespace App\Tests\Repository;
use App\Repository\GenreRepository;
use App\Entity\Genre;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GenreRepositoryTest extends KernelTestCase
{

    private EntityManagerInterface $em;
    protected function setUp(): void{
        self::bootKernel();
        $this->em= static::getContainer()->get(EntityManagerInterface::class);
    }
    public function testSomething(): void
    {
        

        $genres= $this->em->getRepository(Genre::class)->findByNameLike('Pop');
        //dump($genres); array of genre which caontains "Pop"

        $this->assertNotEmpty($genres);

        
    }
}
