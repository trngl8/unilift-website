<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\OrderProductType;
use App\Model\OrderProduct;
use App\Repository\OfferRepository;
use App\Repository\ProductRepository;
use App\Service\OfferService;
use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: "/product", name: "app_product_")]
class ProductController extends AbstractController
{
    private $offerService;

    private $orderService;
    public function __construct(OfferService $offerService, OrderService $orderService)
    {
        $this->offerService = $offerService;
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

    #[Route('/{alias}/show', name: 'show', methods: ['GET'])]
    public function payment(string $alias, ProductRepository $products, OfferRepository $offerRepository) : Response
    {
        $product = $products->findOneBy(['id' => $alias]);

        $offers = $this->offerService->getOffers($product);

        $offers = $offerRepository->findBy([], ['id' => 'ASC'], 1, 0);

        return $this->render('product/show.html.twig', [
            'item' => $product,
            'offers' => $offers
        ]);
    }

    #[Route('/{id}/order', name: 'order', methods: ['GET', 'POST'])]
    public function order(Product $product, Request $request): Response
    {
        $orderRequest = new OrderProduct();
        $form = $this->createForm(OrderProductType::class, $orderRequest);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $order = $this->orderService->orderProduct($product, $orderRequest);

            $this->addFlash('success', sprintf('%s flash.success.created', $order->getUuid()));

            return $this->redirectToRoute('app_product_order_success', ['id' => $product->getId()]);
        }

        return $this->render('product/order_request.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
        ]);
    }

    //TODO: move to the Order module
    #[Route('/{id}/order/success', name: 'order_success', methods: ['GET'])]
    public function success(Product $product): Response
    {
        return $this->render('product/order_success.html.twig', [
            'product' => $product,
        ]);
    }
}
