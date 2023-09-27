<?php

namespace App\Repository;

use App\Entity\EntertainmentReservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EntertainmentReservation>
 *
 * @method EntertainmentReservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method EntertainmentReservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method EntertainmentReservation[]    findAll()
 * @method EntertainmentReservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntertainmentReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EntertainmentReservation::class);
    }

//    /**
//     * @return EntertainmentReservation[] Returns an array of EntertainmentReservation objects
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

//    public function findOneBySomeField($value): ?EntertainmentReservation
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
