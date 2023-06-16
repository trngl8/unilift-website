<?php

namespace App\Tests\Acceptance\Easy;

use App\Controller\EasyAdmin\OrderCrudController;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrderControllerTest extends WebTestCase
{
    public function testEntity(): void
    {
        $target = new OrderCrudController();
        $this->assertEquals('App\Entity\Order', $target->getEntityFqcn());
    }

    public function testOrdersSuccess(): void
    {
        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('admin@test.com');

        $client->loginUser($testUser);

        $client->request('GET', '/easy?crudAction=index&crudControllerFqcn=App%5CController%5CEasyAdmin%5COrderCrudController');
        $this->assertResponseIsSuccessful();
        //$this->assertSelectorTextContains('h1', 'Order');
    }
}
