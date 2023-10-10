<?php

namespace App\Repository;

use App\Entity\Opportunities;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Opportunities>
 *
 * @method Opportunities|null find($id, $lockMode = null, $lockVersion = null)
 * @method Opportunities|null findOneBy(array $criteria, array $orderBy = null)
 * @method Opportunities[]    findAll()
 * @method Opportunities[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OpportunitiesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Opportunities::class);
    }

//    /**
//     * @return Opportunities[] Returns an array of Opportunities objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Opportunities
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
