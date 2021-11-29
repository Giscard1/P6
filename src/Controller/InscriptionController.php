<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\InscriptionType;
use App\Service\InscriptionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class InscriptionController extends AbstractController
{
    //private UserPasswordHasherInterface $passwordHacher;

    /**
     * @var UserPasswordHasherInterface
     */

    public function __construct(UserPasswordHasherInterface $passwordHacher)
    {
        $this->passwordHacher = $passwordHacher;
    }

    /**
     * @Route ("/register", name="register")
     * @param Request $request
     * @param InscriptionService $inscriptionService
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function newUser(Request $request, InscriptionService $inscriptionService)
    {
        $formRegister = $this->createForm(InscriptionType::class)->handleRequest($request);

        if ($formRegister->isSubmitted() && $formRegister->isValid())
        {
            //Récup les données user
            $user = $formRegister->getData();
            //Hash du mot de pass
            $passwordHashed = $this->passwordHacher->hashPassword($user, $user->getPassword());
            $user->setPassword($passwordHashed);
            //persister user
            //$inscriptionService->createNewUser($formRegister->getData());
            $inscriptionService->createNewUser($user);
            return $this->redirectToRoute('homepage');
            $this->addFlash('success', 'new user creat');
        }
        return $this->render('Inscription/inscription.html.twig', ['form' => $formRegister->createView()]);
    }
}
