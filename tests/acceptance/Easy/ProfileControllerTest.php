<?php

namespace App\Tests\Acceptance\Easy;

use App\Controller\EasyAdmin\ProfileCrudController;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProfileControllerTest extends WebTestCase
{
    public function testEntity(): void
    {
        $target = new ProfileCrudController();
        $this->assertEquals('App\Entity\Profile', $target->getEntityFqcn());
    }

    public function testProfilesSuccess(): void
    {
        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('admin@test.com');

        $client->loginUser($testUser);

        $client->request('GET', '/easy?crudAction=index&crudControllerFqcn=App%5CController%5CEasyAdmin%5CProfileCrudController');
        $this->assertResponseIsSuccessful();
        //$this->assertSelectorTextContains('h1', 'Profile');
    }
}
