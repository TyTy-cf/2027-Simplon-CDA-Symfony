<?php

namespace App\Tests\Repository;

use App\Repository\AlbumRepository;
use App\Tests\AbstractKernelTestCase;

class AlbumRepositoryTest extends AbstractKernelTestCase
{
    public function testRepositoryFindAllAlbums(): void
    {
        self::bootKernel();

        $albums = $this->getRepository(AlbumRepository::class)->findAll();

        $this->assertNotEmpty($albums);
    }

    public function testRepositoryFindOneAlbum(): void
    {
        self::bootKernel();

        $album = $this->getRepository(AlbumRepository::class)->findBy(['title' => 'Haven']);

        $this->assertNotEmpty($album);
        $this->assertInstanceOf(Album::class, $album[0]);
    }
}
