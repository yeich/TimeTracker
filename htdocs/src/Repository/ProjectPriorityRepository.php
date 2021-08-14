<?php

namespace App\Repository;

use App\Entity\ProjectPriority;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProjectPriority|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectPriority|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectPriority[]    findAll()
 * @method ProjectPriority[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectPriorityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectPriority::class);
    }

    // /**
    //  * @return ProjectPriority[] Returns an array of ProjectPriority objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProjectPriority
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
