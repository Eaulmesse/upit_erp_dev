<?php

namespace App\Repository;

use App\Entity\TaxRates;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TaxRates>
 *
 * @method TaxRates|null find($id, $lockMode = null, $lockVersion = null)
 * @method TaxRates|null findOneBy(array $criteria, array $orderBy = null)
 * @method TaxRates[]    findAll()
 * @method TaxRates[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaxRatesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TaxRates::class);
    }

//    /**
//     * @return TaxRates[] Returns an array of TaxRates objects
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

//    public function findOneBySomeField($value): ?TaxRates
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
