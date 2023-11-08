<?php

namespace App\Repository;

use App\Entity\PreTaxAmount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PreTaxAmount>
 *
 * @method PreTaxAmount|null find($id, $lockMode = null, $lockVersion = null)
 * @method PreTaxAmount|null findOneBy(array $criteria, array $orderBy = null)
 * @method PreTaxAmount[]    findAll()
 * @method PreTaxAmount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PreTaxAmountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PreTaxAmount::class);
    }

//    /**
//     * @return PreTaxAmount[] Returns an array of PreTaxAmount objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PreTaxAmount
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
