<?php

namespace App\Repository;

use App\Entity\ProductCommunication;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ProductCommunication|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductCommunication|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductCommunication[]    findAll()
 * @method ProductCommunication[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductCommunicationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProductCommunication::class);
    }

    // /**
    //  * @return ProductCommunication[] Returns an array of ProductCommunication objects
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
    public function findOneBySomeField($value): ?ProductCommunication
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
