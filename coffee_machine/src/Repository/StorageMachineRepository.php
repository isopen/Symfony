<?php

namespace App\Repository;

use App\Entity\StorageMachine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method StorageMachine|null find($id, $lockMode = null, $lockVersion = null)
 * @method StorageMachine|null findOneBy(array $criteria, array $orderBy = null)
 * @method StorageMachine[]    findAll()
 * @method StorageMachine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StorageMachineRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, StorageMachine::class);
    }

    // /**
    //  * @return MachineStorage[] Returns an array of StorageMachine objects
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
    public function findOneBySomeField($value): ?StorageMachine
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
