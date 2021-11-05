<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\InscriptionType;
use App\Service\InscriptionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class InscriptionController extends AbstractController
{
    /**
     * @Route ("/register", name="register")
     * @param Request $request
     * @param InscriptionService $inscriptionService
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function newUser(Request $request, InscriptionService $inscriptionService)
    {
        $user = new User();
        $form = $this->createForm(InscriptionType::class, $user)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $inscriptionService->createNewUser($user);
            $this->addFlash('success', 'new user creat');
        }
        return $this->render('Inscription/inscription.html.twig', ['form' => $form->createView()]);
    }
}
