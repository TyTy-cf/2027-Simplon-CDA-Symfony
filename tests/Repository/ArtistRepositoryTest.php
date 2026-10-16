<?php

namespace App\Tests;

use App\Entity\Artist;
use Doctrine\ORM\EntityManagerInterface;
use Override;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ArtistRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $em;
    #[Override]
    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->em=static::getContainer()->get(EntityManagerInterface::class);

    }
    public function testFindArtistByName(): void
    {
        $artist = $this->em
        ->getRepository(Artist::class)
        ->findOneBy(['stageName'=>'Woodkid']);

        $this->assertNotEmpty($artist);
        $this->assertEquals($artist->getStageName(), "Woodkid");
    }

    public function testFindAllArtist(): void
    {
        $artists = $this->em
        ->getRepository(Artist::class)
        ->findAll();

        $this->assertNotEmpty($artists);
        $this->assertEquals(count($artists), 38);
    }
}
