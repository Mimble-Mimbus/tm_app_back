<?php

namespace App\Repository;

use App\Entity\AUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AUser>
 *
 * @method AUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method AUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method AUser[]    findAll()
 * @method AUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AUser::class);
    }

//    /**
//     * @return AUser[] Returns an array of AUser objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AUser
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
