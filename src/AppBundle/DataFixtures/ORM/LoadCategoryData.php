<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Category;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Description of LoadCategoryData
 *
 * @author kamil
 */
class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();

        for ($i = 1; $i <= 100; $i++) {

            $category = new Category();
            $category->setName(ucfirst($faker->sentence(2)));
            $manager->persist($category);
            
            $this->addReference('category-' . $i, $category);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
