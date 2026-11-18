<?php

namespace App\Repository;

use App\Entity\Album;
use App\Entity\Track;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Track>
 */
class TrackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Track::class);
    }

    public function findTotalDuration(Album $album): string
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.duration)')
            ->andWhere('t.album = :album')
            ->setParameter('album', $album)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findTotalDurationWithId($id): string
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.duration)')
            ->join('a', 't.album')
            ->andWhere('a.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleScalarResult();
    }

    //    /**
    //     * @return Track[] Returns an array of Track objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Track
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
