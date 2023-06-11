<?php

namespace App\Controller;

use App\Button\LinkToRoute;
use App\Form\OrderProductType;
use App\Repository\ProductRepository;
use Doctrine\DBAL\Exception\DriverException;
use Doctrine\DBAL\Exception\TableNotFoundException;
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
            'order_form' => $form->createView(),
        ]);
    }

    public function info(): Response
    {
        return $this->render('default/info.html.twig');
    }

    public function contact(): Response
    {
        $form = $this->createFormBuilder()
            ->getForm();

        return $this->render('default/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
