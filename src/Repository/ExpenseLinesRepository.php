<?php

namespace App\Repository;

use App\Entity\ExpenseLines;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ExpenseLines>
 *
 * @method ExpenseLines|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExpenseLines|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExpenseLines[]    findAll()
 * @method ExpenseLines[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExpenseLinesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExpenseLines::class);
    }

//    /**
//     * @return ExpenseLines[] Returns an array of ExpenseLines objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ExpenseLines
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
