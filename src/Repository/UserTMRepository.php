<?php

namespace App\Repository;

use App\Entity\UserTM;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserTM>
 *
 * @method UserTM|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserTM|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserTM[]    findAll()
 * @method UserTM[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserTMRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserTM::class);
    }

//    /**
//     * @return UserTM[] Returns an array of UserTM objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserTM
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
