<?php

namespace App\Repository;

use App\Entity\ProjetNature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProjetNature>
 *
 * @method ProjetNature|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjetNature|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjetNature[]    findAll()
 * @method ProjetNature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjetNatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjetNature::class);
    }

//    /**
//     * @return ProjetNature[] Returns an array of ProjetNature objects
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

//    public function findOneBySomeField($value): ?ProjetNature
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
