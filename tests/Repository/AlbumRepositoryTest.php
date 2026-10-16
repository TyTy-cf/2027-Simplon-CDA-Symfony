<?php

namespace App\Tests\Repository;

use App\Repository\AlbumRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use PHPUnit\Framework\Attributes\TestWith;

class AlbumRepositoryTest extends KernelTestCase
{

    /* ON TROUVE LE TITRE */
    public function testSuccessSearchByTitle(): void
    {
        $titles = $this->searchTitles('Ride');

        $this->assertContains('Ride On Time', $titles);
    }

    /* PAS SENSIBLE A LA CASSE */
    public function testSearchByTitleNoCase(): void
    {
        $titles = $this->searchTitles('ride');

        $this->assertContains('Ride On Time', $titles);
    }

    /* AUCUN RÉSULTAT QUAND IL Y A RIEN QUI CORRESPOND */
    public function testSearchByTitleNoResults(): void
    {
        $titles = $this->searchTitles('Son inexistant');

        $this->assertSame([], $titles);
    }

    /* RECHERCHE VIDE = TOUS LES ALBUMS*/
    public function testEmptySearchGivesAllAlbums(): void
    {
        $titles = $this->searchTitles('');
        $total = count(static::getContainer()->get(AlbumRepository::class)->findAll());

        $this->assertCount($total, $titles);
    }

    /* MÉTHODE UTILITAIRE */

    private function searchTitles(string $search): array
    {
        self::bootKernel();
        $albumRepository = static::getContainer()->get(AlbumRepository::class);

        $titles = [];
        foreach ($albumRepository->searchByTitle($search) as $album) {
            $titles[] = $album->getTitle();
        }

        return $titles;
    }

    #[TestWith(['ep'])]
    #[TestWith(['single'])]
    #[TestWith(['album'])]

    /* TEST DU BON TYPE D'ALBUM ET DU BON NOMBRE */
    public function testFindType(string $type): void
    {
        self::bootKernel();
        $albums = static::getContainer()->get(AlbumRepository::class)->findWithRelations($type, 12);

        $this->assertNotEmpty($albums);
        $this->assertLessThanOrEqual(12, count($albums));
        foreach ($albums as $album) {
            $this->assertSame($type, strtolower($album->getType()));
        }
    }
}