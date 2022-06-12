<?php


namespace App\Service;

use App\Entity\User;

class InscriptionService extends BaseService
{


    public function createNewUser(\App\Entity\User $user)
    {
        $this->persist($user);
        $this->flush();
    }

    public function upDateUser(\App\Entity\User $user)
    {
        $this->persist($user);
        $this->flush();
    }

    public function upDateUserPassword(string $email, string $password,\App\Entity\User $user)
    {
        $passwordHashed = $this->passwordHacher->hashPassword($user, $user->getPassword());
        $user->setPassword($passwordHashed);
        $user->setEmail($email);
        $user->setUpdateDate(New \DateTime());
        $user->setToken(null);
        $this->flush();
    }
}
