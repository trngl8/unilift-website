<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $userAdmin = new User();
        $userAdmin->setUsername('user@test.com');
        $userAdmin->setPassword('pass');
        $userAdmin->addRole('ROLE_USER');

        $user = new User();
        $user->setUsername('admin@test.com');
        $user->setPassword('password');
        $user->addRole('ROLE_ADMIN');

        $manager->persist($userAdmin);
        $manager->persist($user);
        $manager->flush();
    }
}
