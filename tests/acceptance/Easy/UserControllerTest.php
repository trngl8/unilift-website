<?php

namespace App\Tests\Acceptance\Easy;

use App\Controller\EasyAdmin\UserCrudController;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testEntity(): void
    {
        $target = new UserCrudController();
        $this->assertEquals('App\Entity\User', $target->getEntityFqcn());
    }

    public function testPagesSuccess(): void
    {
        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('admin@test.com');

        $client->loginUser($testUser);

        $client->request('GET', '/easy?crudAction=index&crudControllerFqcn=App%5CController%5CEasyAdmin%5CUserCrudController');
        $this->assertResponseIsSuccessful();
        //$this->assertSelectorTextContains('h1', 'User');
    }
}
