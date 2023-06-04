<?php

namespace App\Controller;

use App\Button\LinkToRoute;
use App\Exception\ThemeLayoutNotFoundException;
use App\Repository\ProductRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;

class DefaultController
{
    private $productRepository;

    private $twig;

    private $security;

    public function __construct(ProductRepository $productRepository, Environment $twig, Security $security) {
        $this->productRepository = $productRepository;
        $this->twig = $twig;
        $this->security = $security;
    }

    public function default() : Response
    {
        $templateName ='default/default.html.twig';

        try {
            $template = $this->twig->load($templateName);
        } catch (LoaderError $e) {
            throw new ThemeLayoutNotFoundException("Default template not found");
        }

        $user = $this->security->getUser();

        //TODO: check routes exists
        if(!$user) {
            //$buttons[] = new LinkToRoute('app_register', 'button.register', 'primary', 'bi bi-1-circle');
            $buttons[] = new LinkToRoute('app_login', 'button.login', 'outline-primary', 'bi bi-2-square');
        } else {
            $buttons[] = new LinkToRoute('app_index', 'action.home', 'outline-primary', 'bi bi-1-circle');
        }

        $content = $template->render([
            'buttons' => $buttons,
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
