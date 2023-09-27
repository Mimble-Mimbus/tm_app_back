<?php

namespace App\Repository;

use App\Entity\Entertainment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Entertainment>
 *
 * @method Entertainment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Entertainment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entertainment[]    findAll()
 * @method Entertainment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntertainmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Entertainment::class);
    }

//    /**
//     * @return Entertainment[] Returns an array of Entertainment objects
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

//    public function findOneBySomeField($value): ?Entertainment
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
