<?php

namespace App\Repository;

use App\Entity\RpgReservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RpgReservation>
 *
 * @method RpgReservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method RpgReservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method RpgReservation[]    findAll()
 * @method RpgReservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RpgReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RpgReservation::class);
    }

//    /**
//     * @return RpgReservation[] Returns an array of RpgReservation objects
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

//    public function findOneBySomeField($value): ?RpgReservation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
