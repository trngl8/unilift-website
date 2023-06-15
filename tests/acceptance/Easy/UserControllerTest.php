<?php

namespace App\Tests\Acceptance\Easy;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
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
