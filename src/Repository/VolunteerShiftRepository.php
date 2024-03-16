<?php

namespace App\Repository;

use App\Entity\Event;
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


   public function getShiftsForPlanning($event, $zone, $user): array
   {
    $query = $this->createQueryBuilder('v');

    if ($event != null) {
        $query
        ->andWhere('v.event = :event')
        ->setParameter('event', $event);
    }

    if ($zone != null) {
        $query->andWhere('v.zone = :zone')
        ->setParameter('zone', $zone);
    }

    if ($user != null) {
        $query->andWhere('v.user = :user')
        ->setParameter('user', $user);
    }
    
    return $query
           ->orderBy('v.shiftStart', 'ASC')
           ->getQuery()
           ->getResult()
       ;
   }

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
