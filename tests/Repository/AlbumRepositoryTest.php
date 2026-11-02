<?php

namespace App\Tests\Repository;

use App\Entity\Album;
use App\Repository\AlbumRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\TestWith;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AlbumRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private AlbumRepository $repository;

    protected function setUp(): void {
        // Démarre le kernel Symfony dans l’environnement de test.
        // Nécessaire pour disposer d’une connexion Doctrine fonctionnelle.
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
    public function testSearchMatchesAnywhereInTitle(string $input): void {
        $albums = $this->repository->search($input);
        // $albums = $this->$entityManager
            // ->getRepository(Album::class)
            // ->findByNameLike($input);
        
        $this->assertNotEmpty($albums);

        foreach ($albums as $album) {
            $this->assertStringContainsStringIgnoringCase($input, $album->getTitle());
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
        $album->setReleasedAt(new DateTimeImmutable('2021-01-01'));
        $album->setPoster("uploads/1.jpg");
        $album->setType('ep');
        $album->setCreatedAt(new DateTimeImmutable('2020-01-01'));

        // Ajoute ici artist, type, releasedAt, etc., selon les contraintes de ton entité.

        $entityManager->persist($album);
        $entityManager->flush();

        try {
            $results = $this->repository->search('ZZZ Test');

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