<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Tricks;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $category = new Category();
         //$user = new User();
         //$trick = new Tricks();

         //$manager->persist($category);
         //$manager->persist($user);
         //$manager->persist($trick);


        $manager->flush();
    }
}
