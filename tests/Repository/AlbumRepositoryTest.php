<?php

namespace App\Tests\Repository;

use App\Repository\AlbumRepository;
use PHPUnit\Framework\Attributes\TestWith;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AlbumRepositoryTest extends KernelTestCase
{
    // public function testSomething(): void
    // {
    //     $kernel = self::bootKernel();
    //     $container = static::getContainer();

    //     $this->assertSame('test', $kernel->getEnvironment());
    //     // $routerService = static::getContainer()->get('router');
    //     // $myCustomService = static::getContainer()->get(CustomService::class);
        
    // }
    private AlbumRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->repository = static::getContainer()->get(AlbumRepository::class);
    }
    
    

    public function testSearchReturnsAlbumsContainingTerm(): void
    {
        $albums = $this->repository->search('ride');
        $this->assertNotEmpty($albums);

        foreach ($albums as $album) {
            $this->assertStringContainsStringIgnoringCase('ride', $album->getTitle());
        }
    }

    #[TestWith(['ride'])]
    #[TestWith(['RIDE'])]
    #[TestWith(['on'])]
    public function testSearchMatchesAnywhereInTitle(string $term): void
    {
        $albums = $this->repository->search($term);

        foreach ($albums as $album) {
            $this->assertStringContainsStringIgnoringCase($term, $album->getTitle());
        }
    }
    public function testSearchReturnsEmptyArrayWhenNoMatch(): void
    {
        $albums = $this->repository->search('zzzzzzzzzz');

        $this->assertSame([], $albums);
    }

}


