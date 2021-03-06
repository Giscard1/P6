<?php

namespace App\Repository;

use App\Entity\Tricks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tricks|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tricks|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tricks[]    findAll()
 * @method Tricks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TricksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tricks::class);
    }

    // /**
    //  * @return Tricks[] Returns an array of Tricks objects
    //  */

    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneBySomeField($value): ?Tricks
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function getTrickByLimit(int $page, int $limitPerPage)
    {
        $querybuilder =  $this->createQueryBuilder('t')
            ->setFirstResult(($page - 1) * $limitPerPage)
            ->setMaxResults($limitPerPage)
            ->orderBy('t.updateDate', 'DESC');

        return new Paginator($querybuilder);
    }

    public function findTricById(int $idTrick){
        return $this->findOneBy(['id' => $idTrick]);
    }

    public function getCommentsByLimit(int $int, int $LIMIT_COMMENT_PER_PAGE)
    {
        return $this->createQueryBuilder('t')
            //->where('t.comment')
            ->setFirstResult($int * $LIMIT_COMMENT_PER_PAGE)
            ->setMaxResults($LIMIT_COMMENT_PER_PAGE)
            ->getQuery()
            ->getResult();
    }

    public function getCommentsByLimitx()
    {
        return $this->createQueryBuilder('t')
            ->innerJoin('t.comment',
                'c', Join::WITH, 'c.id = t.comment')
            ->getQuery()
            ->getResult();
    }
}
