<?php


namespace App\Controller;


use App\Entity\Tricks;
use App\Form\TrickType;
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

}
