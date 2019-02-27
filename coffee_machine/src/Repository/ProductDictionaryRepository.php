<?php

namespace App\Repository;

use App\Entity\ProductDictionary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ProductDictionary|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductDictionary|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductDictionary[]    findAll()
 * @method ProductDictionary[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductDictionaryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProductDictionary::class);
    }

    // /**
    //  * @return ProductDictionary[] Returns an array of ProductDictionary objects
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
    public function findOneBySomeField($value): ?ProductDictionary
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
