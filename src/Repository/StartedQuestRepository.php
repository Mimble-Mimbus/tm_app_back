<?php

namespace App\Repository;

use App\Entity\StartedQuest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StartedQuest>
 *
 * @method StartedQuest|null find($id, $lockMode = null, $lockVersion = null)
 * @method StartedQuest|null findOneBy(array $criteria, array $orderBy = null)
 * @method StartedQuest[]    findAll()
 * @method StartedQuest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StartedQuestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StartedQuest::class);
    }

//    /**
//     * @return StartedQuest[] Returns an array of StartedQuest objects
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

//    public function findOneBySomeField($value): ?StartedQuest
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
