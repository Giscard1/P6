<?php


namespace App\Service;


use App\Controller\PasswordController;
use App\Entity\Comment;
use App\Entity\User;
use App\Repository\TricksRepository;
use App\Repository\UserRepository;
use App\Service\InscriptionService;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PasswordService
{
    private UserRepository $userRepository;
    private InscriptionService $inscriptionService;
    private EntityManagerInterface $entityManager;


    public function __construct(
        UserRepository $userRepository,
        InscriptionService $inscriptionService,
        EntityManagerInterface $entityManager
    ) {
        $this->userRepository = $userRepository;
        $this->inscriptionService = $inscriptionService;
        $this->entityManager = $entityManager;
    }

    /**
     * @param UserRepository $userRepository
     * @param InscriptionService $inscriptionService
     * @return User|null
     * @throws \Exception
     * function charger de vérifier si l'email envoyé est lié à un compte
     */
    public function hasAccount($email)
    {
        //retourne un objec user
        return $this->userRepository->findOneBy(['email' => $email]);
    }

    public function hasAccountByToken($token)
    {
        //retourne un objec user
        return $this->userRepository->findOneBy(['token' => $token]);
    }

    public function defineToken(User $user){
        $token = md5(uniqid());
        $user->setToken($token);
        $this->entityManager->flush();
    }

}
