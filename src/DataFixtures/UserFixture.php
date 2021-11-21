<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    public const User_REF = 'user-ref';

    /**
     * Load data fixtures with the passed EntityManager
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setName('Joe');
        $user->setLastName('random');
        $user->setEmail('joeradom@gmail.com');
        $user->setUpdateDate(new \DateTime());
        $user->setPassword('123456');

        $manager->persist($user);

        $this->addReference(self::User_REF, $user);

        $manager->flush();

    }
}
