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
use Symfony\Component\Security\Core\Security;

class TrickController extends AbstractController
{
    public function __construct(Security $security)
    {
        // service de Symfony permettant l'authentification
        $this->Security = $security;
    }

    /**
     * @Route ("/newTrick", name="newTrick")
     * @param Request $request
     * @param TrickService $trickService
     * @param Security $security
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function newTrick(Request $request, TrickService $trickService, Security $security)
    {
        //redirige l'utilisateur vers la homepage s'il n'est pas connecté
        if (!$this->Security->isGranted('IS_AUTHENTICATED_FULLY')){
            return $this->redirectToRoute('homepage');
        }

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
        $trick = $this->getDoctrine()->getRepository(Tricks::class)->find($id);
        $picture = $trick->getPicture();
        $commentForm = $this->createForm(CommentType::class)->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid())
        {
            $comment = $commentForm->getData();
            $commentService->createNewComment($comment);
            $this->addFlash('success', 'new comment creat');
        }
        return $this->render('Trick/showTrick.html.twig', ['trick' => $trick, 'picture' => $picture ,'commentForm' => $commentForm->createView()]);
    }
}
