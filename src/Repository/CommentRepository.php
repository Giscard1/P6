<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Tricks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function getCommentsPagination(int $trickId,int $page, int $limit = 3)
    {
        return $this->createQueryBuilder('c')
            //->innerJoin('c.tricks', 't')
            ->where('c.tricks = :tricks')
            ->setParameter('tricks', $trickId)
            //->orderBy('c.id', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
    // /**
    //  * @return Comment[] Returns an array of Comment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Comment
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function getCommentsByLimit(int $int, int $LIMIT_COMMENT_PER_PAGE)
    {
        return $this->createQueryBuilder('t')
            ->setFirstResult($int * $LIMIT_COMMENT_PER_PAGE)
            ->setMaxResults($LIMIT_COMMENT_PER_PAGE)
            //AJOUTER INNER JOIN AVEC LA TABLE COMMENT POUR RECUPÉRER LA DATE DE CREATION->orderBy('t.creationDAte', 'DESC')
            ->getQuery()
            ->getResult();
    }


    public function findAllTricksById(int $idTrick){
        return $this->findAll(['id' => $idTrick]);
    }
}
