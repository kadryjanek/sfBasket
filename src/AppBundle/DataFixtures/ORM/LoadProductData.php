<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

/**
 * Description of LoadProductData
 *
 * @author kamil
 */
class LoadProductData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(\Doctrine\Common\Persistence\ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();
        
        for ($i=1; $i<=1000; $i++) {
            
            $product = new \AppBundle\Entity\Product();
            $product->setName(ucfirst($faker->sentence(3)));
            $product->setDescription($faker->text);
            $product->setPrice($faker->randomFloat());
            $product->setAmount($faker->numberBetween(0, 20));
            $product->setCategory($this->getReference('category-' . $faker->numberBetween(1, 100)));
            
            $manager->persist($product);
        }
        
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
