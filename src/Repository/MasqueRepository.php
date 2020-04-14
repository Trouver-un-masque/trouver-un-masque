<?php

namespace App\Repository;

use App\Entity\Masque;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Masque|null find($id, $lockMode = null, $lockVersion = null)
 * @method Masque|null findOneBy(array $criteria, array $orderBy = null)
 * @method Masque[]    findAll()
 * @method Masque[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MasqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Masque::class);
    }

    // /**
    //  * @return Masque[] Returns an array of Masque objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Masque
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
