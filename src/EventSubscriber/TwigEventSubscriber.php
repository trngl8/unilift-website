<?php

namespace App\EventSubscriber;

use App\Service\MessageService;
use App\Service\OfferService;
use Doctrine\DBAL\Exception\DriverException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{
    private $twig;

    private $security;

    private $offerService;

    public function __construct(Environment $twig, Security $security, OfferService $offerService)
    {
        $this->twig = $twig;
        $this->security = $security;
        $this->offerService = $offerService;
    }

    public function onControllerEvent(ControllerEvent $event): void
    {
        // Check cart in cookies
        $request = $event->getRequest();
        $cookie = $request->cookies->get('cart');
        $cart = $cookie ? json_decode($cookie, true) : [];

        if($cart) {
            $cartItems = $this->offerService->getCartOrders($cart);

            if(count($cartItems) > 0) {
                //$this->twig->addGlobal('cart_items_count', count($cartItems));
            }
        }

        //TODO: move to the external class
        $topMenu = [
            'index' => [
                'title' => 'Про нас',
                'route' => 'app_pages_show',
                'path' => '/pages/about',
            ],
            'lift' => [
                'title' => 'Ліфти',
                'path' => '#',
                'items' => [
                    [
                        'alias' => 'cottage',
                        'path' => '/product/cottage',
                        'route' => 'app_product_show',
                        'title' => 'Котетджний ліфт',
                    ],
                    [
                        'alias' => 'passenger',
                        'path' => '/product/passenger',
                        'route' => 'app_product_show',
                        'title' => 'Пасажирський ліфт для ЖК',
                    ],
                    [
                        'alias' => 'healthy',
                        'path' => '/product/healthy',
                        'route' => 'app_product_show',
                        'title' => 'Лікарняний ліфт для медичних закладів',
                    ],
                    [
                        'alias' => 'avto',
                        'path' => '/product/avto',
                        'route' => 'app_product_show',
                        'title' => 'Автомобільний ліфт',
                    ],
                    [
                        'alias' => 'low',
                        'path' => '/product/low-weight',
                        'route' => 'app_product_show',
                        'title' => 'Маловантажний ліфт',
                    ],
                ]
            ],
            'low-mobile' => [
                'title' => 'Підйомники для інвалідів',
                'route' => 'app_pages_show',
                'path' => '/product/low-mobile',
            ],
            'platform' => [
                'title' => 'Підйомні платформи',
                'route' => 'app_pages_show',
                'path' => '/product/platform',
            ],
            'escalators' => [
                'title' => 'Ескалатори',
                'route' => 'app_pages_show',
                'path' => '/product/escalators',
            ],
            'travolators' => [
                'title' => 'Траволатори',
                'route' => 'app_pages_show',
                'path' => '/product/travolators',
            ],
        ];

        $this->twig->addGlobal('top_menu', $topMenu);

        // Check user messages
        try {
            $user = $this->security->getUser();
        } catch (DriverException $e) {
            //throw $e;
            $user = null;
        }

        if(!$user)  {
            return;
        }

        //$countMessages = $this->messageService->findIncomingCount($user->getUserIdentifier());

//        if($countMessages > 0) {
//            $this->twig->addGlobal('incoming_unread_count', $countMessages);
//        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ControllerEvent::class => 'onControllerEvent',
        ];
    }
}
