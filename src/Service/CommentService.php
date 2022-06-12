<?php


namespace App\Service;


use App\Entity\Comment;
use App\Repository\TricksRepository;
use App\Repository\UserRepository;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CommentService extends AbstractController
{

    private $userRepo;
    private $trickRepo;
    /**
     * CommentService constructor.
     * @param UserRepository $userRepo
     * @param TrickService $trickRepo
     */
    public function __construct(UserRepository $userRepo, TricksRepository $trickRepo)
    {
        $this->userRepo = $userRepo;
        $this->trickRepo = $trickRepo;
    }

    public function createNewComment ($commentContent, $idTrick, $user)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $trick = $this->trickRepo->findTricById($idTrick);
       // $user = $this->userRepo->findUserById($idUser);

        $comment = new Comment();
        $comment->setContent($commentContent);
        $comment->setUser($user);
        $comment->setTricks($trick);
        $comment->setCreationDAte(new \DateTime());
        //$this->persist($comment);
        //$this->flush();
        $entityManager->persist($comment);
        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
    }

}
