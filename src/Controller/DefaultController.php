<?php

namespace App\Controller;

use App\Button\LinkToRoute;
use App\Exception\ThemeLayoutNotFoundException;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;

class DefaultController
{
    private $productRepository;

    private $twig;

    public function __construct(ProductRepository $productRepository, Environment $twig) {
        $this->productRepository = $productRepository;
        $this->twig = $twig;
    }

    public function default() : Response
    {
        $templateName ='default/default.html.twig';

        try {
            $template = $this->twig->load($templateName);
        } catch (LoaderError $e) {
            throw new ThemeLayoutNotFoundException("Default template not found");
        }

        //TODO: check routes exists
        $button1 = new LinkToRoute('app_register', 'button.register', 'primary', 'bi bi-1-circle');
        $button2 = new LinkToRoute('app_login', 'button.login', 'outline-primary', 'bi bi-2-square');

        $products = $this->productRepository->findBy([], ['id' => 'ASC'], 3, 0);

        $content = $template->render([
            'buttons' => [$button1, $button2],
            'products' => $products,
        ]);

        $response ??= new Response();
        $response->setContent($content);

        return $response;
    }

    public function index() : Response
    {
        $templateName = 'default/index.html.twig';

        try {
            $template = $this->twig->load($templateName);
        } catch (LoaderError $e) {
            throw new ThemeLayoutNotFoundException("Index template not found");
        }

        $products = $this->productRepository->findBy([], ['id' => 'ASC'], 3, 0);

        //TODO: check routes exists
        $button1 = new LinkToRoute('default', 'button.more', 'primary', 'bi bi-1-circle');
        $button2 = new LinkToRoute('default_index', 'button.subscribe', 'outline-primary', 'bi bi-2-square');
        $button3 = new LinkToRoute('default_index', 'button.light', 'light');

        $content = $template->render([
            'buttons' => [$button1, $button2, $button3],
            'products' => $products,
        ]);

        $response ??= new Response();
        $response->setContent($content);

        return $response;
    }
}
