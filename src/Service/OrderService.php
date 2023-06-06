<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\Product;
use App\Model\OrderProduct;
use Doctrine\Persistence\ManagerRegistry;

class OrderService
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function orderProduct(Product $product, OrderProduct $orderRequest): Order
    {
        $order = new Order();
        $order->setDeliveryPhone($orderRequest->phone);
        $order->setDescription($orderRequest->description);
        $order->setDeliveryEmail($orderRequest->email);
        $order->setAmount($product->getFees());
        $order->setCurrency('UAH');

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($order);
        $entityManager->flush();

        return $order;
    }
}
