<?php

namespace App\Controller\EasyAdmin;

use App\Entity\Order;
use App\Entity\Page;
use App\Entity\Product;
use App\Entity\Profile;
use App\Entity\Topic;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Locale;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/easy', name: 'easy_admin')]
    public function index(): Response
    {
        //return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend

        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(TopicCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
        ->setTitle('Unilift Website')
        ->setTitle('<img src="/build/images/logo.png" width="200" />')

        // <link rel="shortcut icon" href="{{ asset('...') }}">
        ->setFaviconPath('favicon.svg')

        ->setTranslationDomain('messages')
        ->setTextDirection('ltr')
        ->renderContentMaximized()
        //->renderSidebarMinimized()

        ->disableDarkMode()
        ->generateRelativeUrls()

        ->setLocales(['en', 'uk'])
        ->setLocales([
            'en' => 'English',
            'uk' => 'Ukrainian'
        ])
        ->setLocales([
            'en', // locale without custom options
            Locale::new('uk', 'ukrainian', 'far fa-language')
        ]);
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Головна панель', 'fa fa-home'),

            MenuItem::section('Задачі'),
            MenuItem::linkToCrud('Задачі', 'fa fa-tasks', Topic::class),

            MenuItem::section('Наповнення'),
            MenuItem::linkToCrud('Замовлення', 'fa fa-money', Order::class),
            MenuItem::linkToCrud('Продукти', 'fa fa-tags', Product::class),
            MenuItem::linkToCrud('Сторінки', 'fa fa-file', Page::class),

            MenuItem::section('Додатково'),
            MenuItem::linkToCrud('Профілі', 'fa fa-user-o', Profile::class),
            MenuItem::linkToCrud('Користувачі', 'fa fa-user', User::class),
        ];
    }
}
