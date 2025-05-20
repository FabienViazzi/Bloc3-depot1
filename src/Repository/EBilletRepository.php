<?php

namespace App\Repository;

use App\Entity\EBillet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EBillet>
 *
 * @method EBillet|null find($id, $lockMode = null, $lockVersion = null)
 * @method EBillet|null findOneBy(array $criteria, array $orderBy = null)
 * @method EBillet[]    findAll()
 * @method EBillet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EBilletRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EBillet::class);
    }

//    /**
//     * @return EBillet[] Returns an array of EBillet objects
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

//    public function findOneBySomeField($value): ?EBillet
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
