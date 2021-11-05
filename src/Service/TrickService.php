<?php


namespace App\Service;

use App\Entity\Tricks;

class TrickService extends BaseService
{

    public function createNewTrick(\App\Entity\Tricks $trick)
    {
        $trick->setCreationDAte( new \DateTime());
        $trick->setUser(1);
        $trick->persist($trick);
        $trick->flush();
    }
}
