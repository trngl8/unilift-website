<?php

namespace App\Tests\Acceptance\Easy;

use App\Controller\EasyAdmin\ProductCrudController;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    public function testEntity(): void
    {
        $target = new ProductCrudController();
        $this->assertEquals('App\Entity\Product', $target->getEntityFqcn());
    }

    public function testProductsSuccess(): void
    {
        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('admin@test.com');

        $client->loginUser($testUser);

        $client->request('GET', '/easy?crudAction=index&crudControllerFqcn=App%5CController%5CEasyAdmin%5CProductCrudController');
        $this->assertResponseIsSuccessful();
        //$this->assertSelectorTextContains('h1', 'Product');
    }
}
