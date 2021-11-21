<?php


namespace App\DataFixtures;


use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixture extends Fixture
{
    public const Category_REF = 'category-ref';

    /**
     * Load data fixtures with the passed EntityManager
     */
    public function load(ObjectManager $manager)
    {
        $category1 = new Category();
        $category1->setName('Les grabs');
        $category1->setCreationDate(new \DateTime());

        $category2 = new Category();
        $category2->setName('Les rotations');
        $category2->setCreationDate(new \DateTime());

        $category3 = new Category();
        $category3->setName('Les flips');
        $category3->setCreationDate(new \DateTime());

        $category4 = new Category();
        $category4->setName('Les slides');
        $category4->setCreationDate(new \DateTime());

        $manager->persist($category1);
        $manager->persist($category2);
        $manager->persist($category3);
        $manager->persist($category4);

        $this->addReference(self::Category_REF, $category1);

        $manager->flush();
    }
}
