<?php

namespace App\Repository;

use App\Entity\FullfilledQuest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FullfilledQuest>
 *
 * @method FullfilledQuest|null find($id, $lockMode = null, $lockVersion = null)
 * @method FullfilledQuest|null findOneBy(array $criteria, array $orderBy = null)
 * @method FullfilledQuest[]    findAll()
 * @method FullfilledQuest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FullfilledQuestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FullfilledQuest::class);
    }

//    /**
//     * @return FullfilledQuest[] Returns an array of FullfilledQuest objects
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

//    public function findOneBySomeField($value): ?FullfilledQuest
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
