<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param int $limit
     *
     * @return mixed
     */
    public function findBestUsers($limit){
        return $this->createQueryBuilder('u')
            // Joindre les annonces de l'utilisateur en question
                    ->join('u.ads', 'a')
            // Joindre les commentaires de l'annonce
                    ->join('a.comments', 'c')
            // Je recupère la moyenne des notations données aux commentaires des annonces qui appartiennent à l'utilisateur
                    ->select('u as user, AVG(c.rating) as avgRatings, COUNT(c) as sumComments')
                    ->groupBy('u')
            // il faut qu'il ai plus de 5 commentaires
                    ->having('sumComments > 5')
                    ->orderBy('avgRatings', 'DESC')
                    ->setMaxResults($limit)
                    ->getQuery()
                    ->getResult();
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
