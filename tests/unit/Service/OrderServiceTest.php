<?php

namespace App\Tests\Unit\Service;

use App\Entity\Order;
use App\Entity\Product;
use App\Model\OrderProduct;
use App\Repository\OrderRepository;
use App\Service\MailerService;
use App\Service\OrderService;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Uid\Uuid;

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
        $mailerService = $this->createMock(MailerService::class);

        $logger = $this->createMock(LoggerInterface::class);

        $service = new OrderService($doctrine, $logger, $mailerService);
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
        $mailerService = $this->createMock(MailerService::class);

        $logger = $this->createMock(LoggerInterface::class);

        $service = new OrderService($doctrine, $logger, $mailerService);
        $result = $service->orderProduct($product, $orderProduct);

        $this->assertEquals('new', $result->getStatus());
        $this->assertEquals(100, $result->getAmount());
        $this->assertEquals('+380000000000', $result->getDeliveryPhone());
        $this->assertEquals('test@test.com', $result->getDeliveryEmail());
        $this->assertEquals('UAH', $result->getCurrency());
        $this->assertEquals('pay', $result->getAction());
    }

    public function testGetOrderException(): void
    {
        $orderRepository = $this->createMock(OrderRepository::class);
        $entityManager = $this->createMock(EntityManager::class);
        $doctrine = $this->createMock(ManagerRegistry::class);
        $doctrine->method('getManager')->willReturn($entityManager);
        $doctrine->method('getRepository')->willReturn($orderRepository);
        $mailerService = $this->createMock(MailerService::class);

        $logger = $this->createMock(LoggerInterface::class);

        $service = new OrderService($doctrine, $logger, $mailerService);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Order test not found');
        $service->getOrder('test');
    }

    public function testGetOrderSuccess(): void
    {
        $order = new Order();
        $order->setAmount(0);
        $order->setCurrency('UAH');
        $order->setDeliveryEmail('test@test.com');
        $order->setDeliveryPhone('+380000000000');
        $order->setDescription('Product Description');
        $order->setStatus('new');
        $order->setAction('pay');

        $orderRepository = $this->createMock(OrderRepository::class);
        $entityManager = $this->createMock(EntityManager::class);
        $doctrine = $this->createMock(ManagerRegistry::class);
        $orderRepository->method('findOneBy')->willReturn($order);
        $doctrine->method('getManager')->willReturn($entityManager);
        $doctrine->method('getRepository')->willReturn($orderRepository);
        $mailerService = $this->createMock(MailerService::class);

        $logger = $this->createMock(LoggerInterface::class);

        $service = new OrderService($doctrine, $logger, $mailerService);
        $result = $service->getOrder('test');
        $this->assertEquals('pay', $result->getAction());
        $this->assertIsString($result->getUuid());
    }
}
