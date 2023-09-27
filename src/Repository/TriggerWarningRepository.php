<?php

namespace App\Repository;

use App\Entity\TriggerWarning;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TriggerWarning>
 *
 * @method TriggerWarning|null find($id, $lockMode = null, $lockVersion = null)
 * @method TriggerWarning|null findOneBy(array $criteria, array $orderBy = null)
 * @method TriggerWarning[]    findAll()
 * @method TriggerWarning[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TriggerWarningRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TriggerWarning::class);
    }

//    /**
//     * @return TriggerWarning[] Returns an array of TriggerWarning objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TriggerWarning
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
