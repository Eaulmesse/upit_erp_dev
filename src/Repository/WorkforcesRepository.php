<?php

namespace App\Repository;

use App\Entity\Workforces;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Workforces>
 *
 * @method Workforces|null find($id, $lockMode = null, $lockVersion = null)
 * @method Workforces|null findOneBy(array $criteria, array $orderBy = null)
 * @method Workforces[]    findAll()
 * @method Workforces[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkforcesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Workforces::class);
    }

//    /**
//     * @return Workforces[] Returns an array of Workforces objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('w.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Workforces
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
