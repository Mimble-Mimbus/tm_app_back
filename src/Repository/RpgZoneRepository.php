<?php

namespace App\Repository;

use App\Entity\RpgZone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RpgZone>
 *
 * @method RpgZone|null find($id, $lockMode = null, $lockVersion = null)
 * @method RpgZone|null findOneBy(array $criteria, array $orderBy = null)
 * @method RpgZone[]    findAll()
 * @method RpgZone[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RpgZoneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RpgZone::class);
    }

//    /**
//     * @return RpgZone[] Returns an array of RpgZone objects
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

//    public function findOneBySomeField($value): ?RpgZone
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
