<?php

namespace App\Controller;

use App\Button\LinkToRoute;
use App\Form\OrderProductType;
use App\Repository\PageRepository;
use App\Repository\ProductRepository;
use Doctrine\DBAL\Exception\DriverException;
use Doctrine\DBAL\Exception\TableNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly PageRepository $pageRepository)
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
        $form = $this->createForm(OrderProductType::class);

        try {
            $products = $this->productRepository->findMain();
        } catch (TableNotFoundException $e) {
            $this->addFlash('error', sprintf('error.table_not_found_for %s', 'products'));
            $products = [];
        } catch (DriverException $e) {
            $this->addFlash('danger', $e->getMessage());
            $products = [];
        } catch (\Exception $e) {
            // TODO: this code never reaches. You may add custom exception to catch
            throw $e;
        }

        $buttons = [];

        if(count($products) === 0) {
            $this->addFlash('warning', 'No products found');
            $buttons[] = new LinkToRoute('admin', 'button.admin', 'primary');
        }

        //TODO: check routes exists
//        $buttons[] = new LinkToRoute('default', 'button.more', 'primary', 'bi bi-1-circle');
//        $buttons[] = new LinkToRoute('default_index', 'button.subscribe', 'outline-primary', 'bi bi-2-square');

        return$this->render('default/index.html.twig', [
            'buttons' => $buttons,
            'products' => $products,
            //'order_form' => $form->createView(),
        ]);
    }

    #[Route('/info', name: 'app_info', methods: ['GET'])]
    public function info(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('default/info.html.twig');
    }

    #[Route('/contact', name: 'app_contact', methods: ['GET'])]
    public function contact(string $page = 'contact'): Response
    {
        $page = $this->pageRepository->findOneBy(['slug' => $page]);

        if(!$page) {
            throw new NotFoundHttpException('Page not found');
        }

        return $this->render('page/show.html.twig', [
            'page' => $page,
        ]);
    }
}
