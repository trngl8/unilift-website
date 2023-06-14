<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\Product;
use App\Model\OrderProduct;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

class OrderService
{
    private ManagerRegistry $doctrine;

    private LoggerInterface $logger;

    public function __construct(ManagerRegistry $doctrine, LoggerInterface $logger)
    {
        $this->doctrine = $doctrine;
        $this->logger = $logger;
    }

    public function orderProduct(Product $product, OrderProduct $orderRequest): Order
    {
        $order = new Order();
        $order->setDeliveryPhone($orderRequest->phone);
        $order->setDescription($orderRequest->description);
        $order->setDeliveryEmail($orderRequest->email);
        $order->setAmount($product->getFees());
        $order->setCurrency('UAH');

        //TODO: send email to customer
        //TODO: send notify message to admin
        try {
            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($order);
            $entityManager->flush();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            throw new \RuntimeException('Order not created');
        }

        return $order;
    }

    public function getOrder(string $uuid): Order
    {
        $order = $this->doctrine->getRepository(Order::class)->findOneBy(['uuid' => $uuid]);

        if(!$order) {
            throw new \RuntimeException(sprintf('Order %s not found', $uuid));
        }

        return $order;
    }
}
