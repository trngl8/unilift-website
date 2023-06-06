<?php

namespace App\Controller;

use App\Button\LinkToRoute;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    public function __construct(
        private readonly ProductRepository $productRepository)
    {
    }

    public function home(): Response
    {
        return$this->render('default/home.html.twig');
    }

    public function default(): Response
    {
        $user = $this->getUser();

        //TODO: check routes exists
        if(!$user) {
            //$buttons[] = new LinkToRoute('app_register', 'button.register', 'primary', 'bi bi-1-circle');
            $buttons[] = new LinkToRoute('app_login', 'button.login', 'outline-primary', 'bi bi-2-square');
        } else {
            $buttons[] = new LinkToRoute('app_index', 'action.home', 'outline-primary', 'bi bi-1-circle');
        }

        return$this->render('default/default.html.twig', [
            'buttons' => $buttons,
        ]);
    }

    public function index(): Response
    {
        $products = $this->productRepository->findBy([], ['id' => 'ASC'], 3, 0);

        //TODO: check routes exists
        $buttons[] = new LinkToRoute('default', 'button.more', 'primary', 'bi bi-1-circle');
        $buttons[] = new LinkToRoute('default_index', 'button.subscribe', 'outline-primary', 'bi bi-2-square');

        return$this->render('default/index.html.twig', [
            'buttons' => $buttons,
            'products' => $products,
        ]);
    }

    public function info() : Response
    {
        return $this->render('default/info.html.twig');
    }
}
