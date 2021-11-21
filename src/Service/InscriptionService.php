<?php


namespace App\Service;

use App\Entity\User;

class InscriptionService extends BaseService
{

    public function createNewUser(\App\Entity\User $user)
    {
        $user->setRole(0);
        $this->persist($user);
        $this->flush();
    }
}
