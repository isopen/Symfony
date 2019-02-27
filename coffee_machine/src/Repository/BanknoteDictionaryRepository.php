<?php

namespace App\Repository;

use App\Entity\BanknoteDictionary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method BanknoteDictionary|null find($id, $lockMode = null, $lockVersion = null)
 * @method BanknoteDictionary|null findOneBy(array $criteria, array $orderBy = null)
 * @method BanknoteDictionary[]    findAll()
 * @method BanknoteDictionary[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BanknoteDictionaryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BanknoteDictionary::class);
    }

    // /**
    //  * @return BanknotesDictionary[] Returns an array of BanknoteDictionary objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BanknoteDictionary
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
