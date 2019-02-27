<?php

namespace App\Repository;

use App\Entity\PriceDictionary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PriceDictionary|null find($id, $lockMode = null, $lockVersion = null)
 * @method PriceDictionary|null findOneBy(array $criteria, array $orderBy = null)
 * @method PriceDictionary[]    findAll()
 * @method PriceDictionary[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PriceDictionaryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PriceDictionary::class);
    }

    // /**
    //  * @return PriceDictionary[] Returns an array of PriceDictionary objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PriceDictionary
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
