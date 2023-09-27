<?php

namespace App\Repository;

use App\Entity\VolunteerShift;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VolunteerShift>
 *
 * @method VolunteerShift|null find($id, $lockMode = null, $lockVersion = null)
 * @method VolunteerShift|null findOneBy(array $criteria, array $orderBy = null)
 * @method VolunteerShift[]    findAll()
 * @method VolunteerShift[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VolunteerShiftRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VolunteerShift::class);
    }

//    /**
//     * @return VolunteerShift[] Returns an array of VolunteerShift objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?VolunteerShift
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
