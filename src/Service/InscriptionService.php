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
}
