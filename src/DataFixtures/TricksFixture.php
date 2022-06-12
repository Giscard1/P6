<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\User;
use App\Entity\Tricks;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TricksFixture extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $userRandomJoe = $this->getReference(UserFixture::User_REF);
        $category = $this->getReference(CategoryFixture::Category_REF);

        for ($i = 0; $i < 50; $i++){

            $trick = new Tricks();
            $trick->setName(('Non du tricks '. $i));
            $trick->setDescription('Description'. $i);
            $trick->setUser($userRandomJoe);
            $trick->setCreationDate(new \DateTime());
            $trick->setCategory($category);
            $trick->setAlt('this is a picture');
            $trick->setSlug($trick->getName());
            $manager->persist($trick);
        }

        $manager->flush();
    }

    public function getDependencies() {

        return [
            UserFixture::class,
            CategoryFixture::class
        ];
    }
}
