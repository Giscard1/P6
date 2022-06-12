<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\AskForResetPasswordType;
use App\Form\InscriptionType;
use App\Form\NewPasswordType;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use App\Service\InscriptionService;
use App\Service\Mail\mailService;
use App\Service\PasswordService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use function PHPUnit\Framework\stringContains;


class PasswordController extends AbstractController
{
    private $inscriptionService;
    private $passwordService;
    private $mailService;
    private $formFactory;
    private $entityManager;
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * PasswordController constructor.
     * @param InscriptionService $inscriptionService
     * @param RequestStack $requestStack
     * @param PasswordService $passwordService
     * @param mailService $mailService
     */
    public function __construct(InscriptionService $inscriptionService,
                                RequestStack $requestStack, PasswordService $passwordService,
                                mailService $mailService,
                                FormFactoryInterface $formFactory,
                                EntityManagerInterface $entityManager
    ) {
        $this->inscriptionService = $inscriptionService;
        $this->requestStack = $requestStack;
        $this->passwordService = $passwordService;
        $this->mailService = $mailService;
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route ("/reset", name="reset_password")
     * @param Request $request
     * @param mailService $mailService
     * @return Response
     * @throws \Exception
     */

    public function askForResetPassword(Request $request)
    {

        $resetPasswordForm = $this->formFactory->create(AskForResetPasswordType::class)
            ->handleRequest($request);

        if ($resetPasswordForm->isSubmitted() && $resetPasswordForm->isValid())
        {

            $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $resetPasswordForm->getData()->email]);

            if ($user instanceof User){
            $this->passwordService->defineToken($user);
            try {
                $this->mailService->sendEmail($user);
            }catch (\Exception $e){
                var_dump($e);
                exit();
            }
                $this->addFlash('success','Corfirmation' );
                return $this->redirectToRoute('homepage');
            }

        }
        return $this->render('Password/resetPassword.html.twig', ['form' => $resetPasswordForm->createView()]);
    }

    /**
     * @Route("/New_password/{token}", name="newPasword")
     * @param string $token
     * @param User $user
     * @return RedirectResponse|Response
     */
    public function newPassword(string $token, User $user)
    {
        $setNewPasswordForm = $this->createForm(NewPasswordType::class, $user);

        $request = $this->requestStack->getCurrentRequest();
        $setNewPasswordForm->handleRequest($request);

        if ($setNewPasswordForm->isSubmitted() && $setNewPasswordForm->isValid())
        {
            $email = $setNewPasswordForm->get('email')->getData();
            $password = $setNewPasswordForm->get('password')->getData();

            if ($user->getToken() == $token){
                if ($user->getEmail() == $email){
                    $this->inscriptionService->upDateUserPassword($email,$password,$user);
                }
            }

            $this->addFlash('success', 'password updated');
            return $this->redirectToRoute('homepage');
        }
        return $this->render('Password/newPassword.html.twig', ['form' => $setNewPasswordForm->createView()]);
    }
}
