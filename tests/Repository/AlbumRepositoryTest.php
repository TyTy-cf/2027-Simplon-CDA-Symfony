<?php

namespace App\Tests\Repository;

use App\Entity\Album;
use App\Repository\AlbumRepository;
use PHPUnit\Framework\Attributes\TestWith;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AlbumRepositoryTest extends KernelTestCase
{
    private AlbumRepository $repository;

    protected function setUp(): void {
        // Démarre le kernel Symfony dans l’environnement de test.
        // Nécessaire pour disposer d’une connexion Doctrine fonctionnelle. [43][46]
        self::bootKernel();

        // Récupère le repository Album depuis le conteneur de services.
        $this->repository = static::getContainer()->get(AlbumRepository::class);
    }
    
    // ---------- Search ---------
    /**
     * Vérifie que la recherche renvoie des albums dont le titre
     * contient le terme recherché, sans tenir compte de la casse.
     */
    public function testSearchReturnsAlbumsContainingTerm(): void {
        $albums = $this->repository->search('ride');
        // $albums = $this->repository->findAlbumsLike('ride');

        // Sans données de test garanties, cette assertion peut être instable.
        $this->assertNotEmpty($albums);

        foreach ($albums as $album) {
            // $this->assertInstanceOf(Album::class, $album);
            $this->assertStringContainsStringIgnoringCase(
                'ride',
                $album->getTitle()
            );
        }
    }

    /**
     * Vérifie que la recherche trouve le terme n’importe où dans le titre,
      *  / et que la casse n’a pas d’importance.
     */
    #[TestWith(['ride'])]
    #[TestWith(['RIDE'])]
    #[TestWith(['on'])]
    public function testSearchMatchesAnywhereInTitle(string $term): void {
        $albums = $this->repository->search($term);
        // $albums = $this->repository->findAlbumsLike($term);
        
        $this->assertNotEmpty($albums);

        foreach ($albums as $album) {
            $this->assertStringContainsStringIgnoringCase($term, $album->getTitle());
        }
    }
    public function testSearchReturnsEmptyArrayWhenNoMatch(): void {
        $albums = $this->repository->search('aze');
        // $albums = $this->repository->findAlbumsLike('aze');

        $this->assertSame([], $albums);
    }

    // ----------- Relations ----------
    public function testFindWithRelationsFiltersByTypeAndLimitsResults(): void
    {
        $albums = $this->repository->findWithRelations('EP', 2);

        $this->assertLessThanOrEqual(2, count($albums));

        foreach ($albums as $album) {
            $this->assertSame('EP', $album->getType());
            $this->assertNotNull($album->getArtist());
            $this->assertNotEmpty($album->getTracks());
        }
    }

    public function testFindAlbumsLikeCreatesAndCleansUpData(): void
    {
        $entityManager = static::getContainer()->get('doctrine')->getManager();

        $album = new Album();
        $album->setTitle('ZZZ Test Album');
        // Ajoute ici artist, type, releasedAt, etc., selon les contraintes de ton entité.

        $entityManager->persist($album);
        $entityManager->flush();

        try {
            $results = $this->repository->findAlbumsLike('ZZZ Test');

            $this->assertNotEmpty($results);
            $this->assertStringContainsStringIgnoringCase(
                'ZZZ Test',
                $results[0]->getTitle()
            );
        } finally {
            // Garantit le nettoyage même si une assertion échoue.
            $entityManager->remove($album);
            $entityManager->flush();
        }
    }
}