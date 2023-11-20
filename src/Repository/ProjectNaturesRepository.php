<?php

namespace App\Repository;

use App\Entity\ProjectNatures;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProjectNatures>
 *
 * @method ProjectNatures|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectNatures|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectNatures[]    findAll()
 * @method ProjectNatures[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectNaturesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectNatures::class);
    }

//    /**
//     * @return ProjectNatures[] Returns an array of ProjectNatures objects
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

//    public function findOneBySomeField($value): ?ProjectNatures
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
