<?php

namespace App\Service;

use App\Entity\Offer;
use App\Entity\Order;
use App\Model\OrderRequest;
use App\Repository\OrderRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

class OfferService
{
    private $orders;

    private $paymentService;

    private $doctrine;
    public function __construct(ManagerRegistry $doctrine, OrderRepository $orders, LiqpayService $paymentService)
    {
        $this->paymentService = $paymentService;
        $this->orders = $orders;
        $this->doctrine = $doctrine;
    }

    public function getOrder(Uuid $id): Order
    {
        return $this->orders->findOneBy(['uuid' => $id]);
    }

    public function getOrders(string $key, string $identifiers) : array
    {
        return $this->orders->findBy([$key => $identifiers]);
    }

    public function getCartOrders(array $cart) : array
    {
        return $this->orders->findBy(['uuid' => array_values($cart), 'status' => 'new']);
    }

    public function createOrderRequest(Offer $offer) : OrderRequest
    {
        return new OrderRequest($offer);
    }

    public function getFormParams(Order $order, string $resultUrl) : array
    {
        return $this->paymentService->getFormParams([
            'action'         => $order->getAction(),
            'amount'         => $order->getAmount(),
            'currency'       => $order->getCurrency(),
            'description'    => $order->getDescription(),
            'order_id'       => sprintf('XX.ZZ.YYYY.order_%d', $order->getId()),
            'result_url'     => $resultUrl,
            'version'        => '3',
            'language'       => 'en',
            'delivery' => [
                //'email' => $order->getEmail(),
            ]
        ]);
    }

    public function paymentApi(Order $order) : array
    {
        return $this->paymentService->apiCall("request", [
            'action'        => 'status',
            'version'       => '3',
            'order_id'      => sprintf('XX.ZZ.YYYY.order_%d', $order->getId())
        ]);
    }
}
