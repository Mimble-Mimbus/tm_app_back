<?php

namespace App\Repository;

use App\Entity\EntertainmentSchedule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EntertainmentSchedule>
 *
 * @method EntertainmentSchedule|null find($id, $lockMode = null, $lockVersion = null)
 * @method EntertainmentSchedule|null findOneBy(array $criteria, array $orderBy = null)
 * @method EntertainmentSchedule[]    findAll()
 * @method EntertainmentSchedule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntertainmentScheduleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EntertainmentSchedule::class);
    }

//    /**
//     * @return EntertainmentSchedule[] Returns an array of EntertainmentSchedule objects
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

//    public function findOneBySomeField($value): ?EntertainmentSchedule
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
