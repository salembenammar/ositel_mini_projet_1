<?php

namespace AdminBundle\DataFixtures\ORM;

use AdminBundle\Entity\Category;
use AdminBundle\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create 10 category et Tag!
        for ($i = 1; $i <= 10; ++$i) {
            $categgory = new Category();
            $categgory->setTitle('Category ' . $i);
            $manager->persist($categgory);
            ////////////////////////
            $tag = new Tag();
            $tag->setName('Tag ' . $i);
            $manager->persist($tag);
        }
        $manager->flush();
    }
}
