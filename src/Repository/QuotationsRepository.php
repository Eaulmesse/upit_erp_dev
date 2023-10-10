<?php

namespace App\Repository;

use App\Entity\Quotations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Quotations>
 *
 * @method Quotations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quotations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quotations[]    findAll()
 * @method Quotations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuotationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quotations::class);
    }

//    /**
//     * @return Quotations[] Returns an array of Quotations objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Quotations
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
