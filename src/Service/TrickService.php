<?php


namespace App\Service;

use App\Entity\Tricks;

class TrickService extends BaseService
{

    public function createNewTrick(\App\Entity\Tricks $trick)
    {
        $trick->persist($trick);
        $trick->flush();
    }
}
