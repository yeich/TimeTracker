<?php

namespace App\Repository;

use App\Entity\TimestampProject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TimestampProject|null find($id, $lockMode = null, $lockVersion = null)
 * @method TimestampProject|null findOneBy(array $criteria, array $orderBy = null)
 * @method TimestampProject[]    findAll()
 * @method TimestampProject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TimestampProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TimestampProject::class);
    }

    // /**
    //  * @return TimestampProject[] Returns an array of TimestampProject objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TimestampProject
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
