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

        $contact = new Page();
        $contact->setTitle('Test');
        $contact->setDescription('Description');
        $contact->setSlug('contact');
        $contact->setActive(true);

        $manager->persist($page);
        $manager->persist($contact);
        $manager->flush();
    }
}
