<?php


namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Tricks;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Service\CommentService;
use App\Service\TrickService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class TrickController extends AbstractController
{
    /**
     * @Route ("/newTrick", name="newTrick")
     * @param Request $request
     * @param TrickService $trickService
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function newTrick(Request $request, TrickService $trickService)
    {
        $trick = new Tricks();
        $form = $this->createForm(TrickType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $trickService->createNewTrick($trick);
            $this->addFlash('success', 'new trick creat');
        }
        return $this->render('Trick/trick.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route ("/trick/{id}", name="trick")
     * @param Request $request
     * @param CommentService $commentService
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showTrick(int $id, Request $request, CommentService $commentService)
    {
        $comment = new Comment();
        $trick = $this->getDoctrine()->getRepository(Tricks::class)->find($id);
        $commentForm = $this->createForm(CommentType::class)->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid())
        {
            $commentService->createNewComment($comment);
            $this->addFlash('success', 'new comment creat');
        }
        return $this->render('Trick/showTrick.html.twig', ['trick' => $trick, 'commentForm' => $commentForm->createView()]);
    }


}
