<?php

namespace App\Repository;

use App\Entity\OptionGroupe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OptionGroupe|null find($id, $lockMode = null, $lockVersion = null)
 * @method OptionGroupe|null findOneBy(array $criteria, array $orderBy = null)
 * @method OptionGroupe[]    findAll()
 * @method OptionGroupe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OptionGroupeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OptionGroupe::class);
    }

    // /**
    //  * @return OptionGroupe[] Returns an array of OptionGroupe objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OptionGroupe
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
