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

    private $mailerService;

    public function __construct(ManagerRegistry $doctrine, LoggerInterface $logger, MailerService $mailerService)
    {
        $this->doctrine = $doctrine;
        $this->logger = $logger;
        $this->mailerService = $mailerService;
    }

    public function orderProduct(Product $product, OrderProduct $orderRequest): Order
    {
        $order = new Order();
        $order->setDeliveryPhone($orderRequest->phone);
        $order->setDescription($orderRequest->description);
        $order->setDeliveryEmail($orderRequest->email);
        $order->setAmount($product->getFees());
        $order->setCurrency('UAH');

        try {
            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($order);
            $entityManager->flush();

            $this->mailerService->sendOrderCreated($orderRequest);
            $this->mailerService->notifyAdmin($orderRequest);

        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            //TODO: escalate to admin
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
