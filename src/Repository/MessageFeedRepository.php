<?php

namespace App\Repository;

use App\Entity\MessageFeed;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MessageFeed|null find($id, $lockMode = null, $lockVersion = null)
 * @method MessageFeed|null findOneBy(array $criteria, array $orderBy = null)
 * @method MessageFeed[]    findAll()
 * @method MessageFeed[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageFeedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MessageFeed::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(MessageFeed $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(MessageFeed $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findMaxPagination($limit = 10)
    {
        $count = $this->createQueryBuilder('messageFeed')
            ->select('count(messageFeed.id)')
            ->getQuery()
            ->getSingleScalarResult();

        if ($count == 0) {
            return $count;
        }

        $maxPagination = ceil($count / $limit);

        return $maxPagination;
    }

    public function selectByPagination($pagination = 1, $limit = 10)
    {
        $start = ($pagination * $limit) - $limit;

        $queryBuilder = $this->createQueryBuilder('messageFeed')
            ->orderBy('messageFeed.updatedAt', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($start)
        ;

        return $queryBuilder->getQuery()->getResult();
    }

    // /**
    //  * @return MessageFeed[] Returns an array of MessageFeed objects
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
    public function findOneBySomeField($value): ?MessageFeed
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
