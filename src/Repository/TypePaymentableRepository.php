<?php

namespace App\Repository;

use App\Entity\TypePaymentable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypePaymentable>
 *
 * @method TypePaymentable|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypePaymentable|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypePaymentable[]    findAll()
 * @method TypePaymentable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypePaymentableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypePaymentable::class);
    }

//    /**
//     * @return TypePaymentable[] Returns an array of TypePaymentable objects
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

//    public function findOneBySomeField($value): ?TypePaymentable
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
