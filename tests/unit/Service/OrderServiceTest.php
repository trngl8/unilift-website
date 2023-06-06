<?php

namespace App\Tests\Unit\Service;

use App\Entity\Product;
use App\Model\OrderProduct;
use App\Service\OrderService;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Psr\Log\LoggerInterface;

class OrderServiceTest extends TestCase
{
    public function testOrderProductException(): void
    {
        $product = new Product();
        $product->setFees(100);

        $orderProduct = new OrderProduct();
        $orderProduct->phone = '+380000000000';
        $orderProduct->email = 'test@test.com';
        $orderProduct->description = 'Product Description';
        $orderProduct->name = 'Order Name';

        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->method('flush')->willThrowException(new \Exception());
        $doctrine = $this->createMock(ManagerRegistry::class);
        $doctrine->method('getManager')->willReturn($entityManager);

        $logger = $this->createMock(LoggerInterface::class);

        $service = new OrderService($doctrine, $logger);
        $this->expectException(\RuntimeException::class);
        $service->orderProduct($product, $orderProduct);
    }

    public function testOrderProductSuccess(): void
    {
        $product = new Product();
        $product->setFees(100);

        $orderProduct = new OrderProduct();
        $orderProduct->phone = '+380000000000';
        $orderProduct->email = 'test@test.com';
        $orderProduct->description = 'Product Description';
        $orderProduct->name = 'Order Name';

        $entityManager = $this->createMock(EntityManager::class);
        $doctrine = $this->createMock(ManagerRegistry::class);
        $doctrine->method('getManager')->willReturn($entityManager);

        $logger = $this->createMock(LoggerInterface::class);

        $service = new OrderService($doctrine, $logger);
        $result = $service->orderProduct($product, $orderProduct);

        $this->assertEquals('new', $result->getStatus());
        $this->assertEquals(100, $result->getAmount());
        $this->assertEquals('+380000000000', $result->getDeliveryPhone());
        $this->assertEquals('test@test.com', $result->getDeliveryEmail());
        $this->assertEquals('UAH', $result->getCurrency());
        $this->assertEquals('pay', $result->getAction());
    }
}
