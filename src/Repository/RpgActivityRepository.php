<?php

namespace App\Repository;

use App\Entity\RpgActivity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RpgActivity>
 *
 * @method RpgActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method RpgActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method RpgActivity[]    findAll()
 * @method RpgActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RpgActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RpgActivity::class);
    }

//    /**
//     * @return RpgActivity[] Returns an array of RpgActivity objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RpgActivity
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
