<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{

    /**
     * @var Security
     */
    private $Security;

    public function __construct(Security $security)
    {
        // service de Symfony permettant l'authentification
        $this->Security = $security;
    }

    /**
     * @Route("/login", name="security_login")
     * @param AuthenticationUtils $authenticationUtils
     * @param Security $security
     * @return Response
     */
    public function index(AuthenticationUtils $authenticationUtils, Security $security): Response {

         //redirige l'utilisateur vers la homepage s'il est deja connecté
         if ($this->Security->isGranted('IS_AUTHENTICATED_FULLY')){
            return $this->redirectToRoute('homepage');
        }

        /*
         *    // Si l'utilisateur est autre chose qu'anonym ça genere une erreur
        //TODO: TROUVER LA RAISON POUR LAQUELLE LA PAGE D'ERREUR NE S'AFFICHE PAS EN PROD
        $this->denyAccessUnlessGranted('IS_ANONYMOUS');
         */

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('Login/login.html.twig', [
            'last_username' => $lastUsername,
            'error'=> $error,
        ]);
    }
}
