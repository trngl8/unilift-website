<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product = new Product();
        $product->setTitle('Product 1');
        $product->setSlug('test-product-1');
        $product->setText('Product 1');

        $manager->persist($product);
        $manager->flush();
    }
}
