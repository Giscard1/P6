<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\InscriptionType;
use App\Service\CommentService;
use App\Service\InscriptionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class InscriptionController extends AbstractController
{
    /**
     * @var Security
     */
    private $security;

    /**
     * @param UserPasswordHasherInterface $passwordHacher
     * @param Security $security
     */

    public function __construct(UserPasswordHasherInterface $passwordHacher, Security $security)
    {
        $this->passwordHacher = $passwordHacher;
        $this->security = $security;
    }

    /**
     * @Route ("/register", name="register")
     * @param Request $request
     * @param InscriptionService $inscriptionService
     * @param CommentService $commentService
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function newUser(Request $request, InscriptionService $inscriptionService, Security $security, ValidatorInterface $validator)
    {

        //redirige l'utilisateur vers la homepage s'il est connecté
        if ($this->security->isGranted('IS_AUTHENTICATED_FULLY')){
            return $this->redirectToRoute('homepage');
        }

        $formRegister = $this->createForm(InscriptionType::class)->handleRequest($request);

        if ($formRegister->isSubmitted() && $formRegister->isValid())
        {
            $user = $formRegister->getData();

            $errors = $validator->validate($user);
            //Hash du mot de pass
             $passwordHashed = $this->passwordHacher->hashPassword($user, $user->getPassword());
             $user->setPassword($passwordHashed);
             $inscriptionService->createNewUser($user);
             $this->addFlash('success', 'new user creat');
             return $this->redirectToRoute('security_login');
        }
        else{
            echo $formRegister->getErrors();
        }
        return $this->render('Inscription/inscription.html.twig', ['form' => $formRegister->createView()]);
    }
}
