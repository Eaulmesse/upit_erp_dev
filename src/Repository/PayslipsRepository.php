<?php

namespace App\Repository;

use App\Entity\Payslips;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Payslips>
 *
 * @method Payslips|null find($id, $lockMode = null, $lockVersion = null)
 * @method Payslips|null findOneBy(array $criteria, array $orderBy = null)
 * @method Payslips[]    findAll()
 * @method Payslips[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PayslipsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Payslips::class);
    }

//    /**
//     * @return Payslips[] Returns an array of Payslips objects
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

//    public function findOneBySomeField($value): ?Payslips
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
