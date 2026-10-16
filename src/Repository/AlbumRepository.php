<?php

namespace App\Repository;

use App\Entity\Album;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Album>
 */
class AlbumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Album::class);
    }

    public function findWithRelations(string $type, ?int $limit = null): array
    {
        // SELECT a.* FROM album AS a
        $qb = $this->createQueryBuilder('a')
            // SELECT a.*, ar.*, t.* FROM album AS a
            ->addSelect('ar', 't')
            // JOIN artist AS ar ON ar.id = a.artist_id
            ->join('a.artist', 'ar')
            // JOIN track AS t ON t.album_id = a.id
            ->join('a.tracks', 't')
            // WHERE A.type = ? ("ep", "album", "single")
            ->where('a.type = :type')
            ->setParameter('type', $type)
            // ORDER BY a.released_at DESC
            ->orderBy('a.releasedAt', 'DESC');

        if ($limit !== null) {
            // LIMIT $limit
            $qb->setMaxResults($limit)
                ->groupBy('a');
        }

        return $qb->getQuery()
            ->getResult(); // $pdo->execute($statement);
    }

    public function findByTitleLike(string $search): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.title LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult();
    }
}
