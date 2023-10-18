<?php

namespace App\Repository;

use App\Entity\QuotationLines;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuotationLines>
 *
 * @method QuotationLines|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuotationLines|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuotationLines[]    findAll()
 * @method QuotationLines[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuotationLinesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuotationLines::class);
    }

    

//    /**
//     * @return QuotationLines[] Returns an array of QuotationLines objects
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

//    public function findOneBySomeField($value): ?QuotationLines
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
