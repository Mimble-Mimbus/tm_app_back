<?php

namespace App\Repository;

use App\Entity\FulfilledQuest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FulfilledQuest>
 *
 * @method FulfilledQuest|null find($id, $lockMode = null, $lockVersion = null)
 * @method FulfilledQuest|null findOneBy(array $criteria, array $orderBy = null)
 * @method FulfilledQuest[]    findAll()
 * @method FulfilledQuest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FulfilledQuestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FulfilledQuest::class);
    }

//    /**
//     * @return FulfilledQuest[] Returns an array of FulfilledQuest objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FulfilledQuest
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
