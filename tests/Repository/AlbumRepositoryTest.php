<?php

namespace App\Tests\Repository;

use App\Entity\Album;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\TestWith;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AlbumRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $em;
    protected function setUp(): void
    {
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
    }

    #[TestWith(['ide'])]
    #[TestWith(['Ride'])]
    public function testFindByNameLike(string $input)
    {
        $albums = $this->em
            ->getRepository(Album::class)
            ->findByNameLike($input);
        foreach ($albums as $album) {
            $this->assertStringContainsString($input, $album->getTitle(), "titre KO");
        }
    }
}
