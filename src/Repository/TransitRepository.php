<?php

namespace App\Repository;

use App\Entity\Transit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transit>
 *
 * @method Transit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transit[]    findAll()
 * @method Transit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transit::class);
    }

//    /**
//     * @return Transit[] Returns an array of Transit objects
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

//    public function findOneBySomeField($value): ?Transit
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
