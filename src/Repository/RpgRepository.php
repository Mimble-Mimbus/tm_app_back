<?php

namespace App\Repository;

use App\Entity\Rpg;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rpg>
 *
 * @method Rpg|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rpg|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rpg[]    findAll()
 * @method Rpg[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RpgRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rpg::class);
    }

//    /**
//     * @return Rpg[] Returns an array of Rpg objects
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

//    public function findOneBySomeField($value): ?Rpg
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
