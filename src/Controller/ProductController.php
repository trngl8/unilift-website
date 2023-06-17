<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\OrderProductType;
use App\Model\OrderProduct;
use App\Repository\ProductRepository;
use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: "/product", name: "app_product_")]
class ProductController extends AbstractController
{
    private $orderService;
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    #[Route('', name: 'index')]
    public function index(ProductRepository $products): Response
    {
        $products = $products->findAll();

        if(count($products) === 0) {
            $this->addFlash('warning', 'No products found');
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/{alias}', name: 'default', methods: ['GET'])]
    public function default(string $alias, ProductRepository $products): Response
    {
        //TODO: set canonical url
        return $this->forward('App\Controller\ProductController::show', [
            'alias' => $alias,
            'products' => $products,
        ]);
    }

    #[Route('/{id}/show', name: 'id', methods: ['GET'])]
    public function id(Product $id): Response
    {
        return $this->render('product/show.html.twig', [
            'item' => $id,
        ]);
    }

    #[Route('/{alias}/show', name: 'show', methods: ['GET'])]
    public function show(string $alias, ProductRepository $products): Response
    {
        $product = $products->findOneBy(['slug' => $alias]);

        return $this->render('product/show.html.twig', [
            'item' => $product,
        ]);
    }

    #[Route('/{id}/order', name: 'order', methods: ['GET', 'POST'])]
    public function order(Product $product, Request $request): Response
    {
        $form = $this->createForm(OrderProductType::class, new OrderProduct());

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $order = $this->orderService->orderProduct($product, $form->getData());

            $this->addFlash('success', sprintf('Order %s flash.success.created', $order->getUuid()));

            return $this->redirectToRoute('app_order_success', ['uuid' => $order->getUuid()]);
        }

        return $this->render('product/order_request.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
        ]);
    }
}
