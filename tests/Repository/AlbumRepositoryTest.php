<?php

namespace App\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Repository\AlbumRepository;
use PHPUnit\Framework\Attributes\TestWith;

class AlbumRepositoryTest extends KernelTestCase
{
    #[TestWith(['single'])]
    #[TestWith(['EP'])]
    #[TestWith(['album'])]
    public function testFindAlbumByType(string $type): void
    {
        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());

        $albumRepo = static::getContainer()->get(AlbumRepository::class);

        $list = $albumRepo->findWithRelations($type, 5);

        $this->assertNotEmpty($list);
        $this->assertLessThanOrEqual(5, count($list));

        foreach ($list as $album) {
            $this->assertSame($type, $album->getType());
        }
    }
}
