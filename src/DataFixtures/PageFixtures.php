<?php

namespace App\DataFixtures;

use App\Entity\Page;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PageFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $page = new Page();
        $page->setTitle('Test');
        $page->setDescription('Description');
        $page->setSlug('test');
        $page->setActive(true);
        $manager->persist($page);
        $manager->flush();
    }
}
