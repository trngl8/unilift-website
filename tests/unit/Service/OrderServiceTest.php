<?php

namespace App\Tests\Unit\Service;

use App\Entity\Product;
use App\Model\OrderProduct;
use App\Service\OrderService;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Doctrine\ManagerRegistry;

class OrderServiceTest extends TestCase
{
    public function testSuccess(): void
    {
        $product = new Product();
        $product->setFees(100);

        $orderProduct = new OrderProduct();
        $orderProduct->phone = '+380000000000';
        $orderProduct->email = 'test@test.com';
        $orderProduct->description = 'Product Description';
        $orderProduct->name = 'Product Name';

        $entityManager = $this->createMock(EntityManager::class);
        $doctrine = $this->createMock(ManagerRegistry::class);
        $doctrine->method('getManager')->willReturn($entityManager);

        $service = new OrderService($doctrine);
        $result = $service->orderProduct($product, $orderProduct);
        //TODO: may be deeper test
        $this->assertEquals('new', $result->getStatus());
    }
}
