<?php

namespace App\Repository;

use App\Entity\RpgTable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RpgTable>
 *
 * @method RpgTable|null find($id, $lockMode = null, $lockVersion = null)
 * @method RpgTable|null findOneBy(array $criteria, array $orderBy = null)
 * @method RpgTable[]    findAll()
 * @method RpgTable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RpgTableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RpgTable::class);
    }

//    /**
//     * @return RpgTable[] Returns an array of RpgTable objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RpgTable
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
