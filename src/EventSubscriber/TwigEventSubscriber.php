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

    private $messageService;

    private $offerService;

    public function __construct(Environment $twig, Security $security, MessageService $messageService, OfferService $offerService)
    {
        $this->twig = $twig;
        $this->security = $security;
        $this->messageService = $messageService;
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

        $topMenu = [
            'index' => [
                'title' => 'Про нас',
                'route' => 'app_pages_show',
                'path' => '/pages/about',
            ],
            'lift' => [
                'title' => 'Ліфти',
                'url' => '/product',
                'items' => [
                    [
                        'alias' => 'cottage',
                        'path' => '/pages/cottage',
                        'route' => 'app_pages_show',
                        'title' => 'Котетджний ліфт',
                    ],
                    [
                        'alias' => 'passenger',
                        'path' => '/pages/passenger',
                        'route' => 'app_pages_show',
                        'title' => 'Пасажирський ліфт для ЖК',
                    ],
                    [
                        'alias' => 'healthy',
                        'path' => '/pages/healthy',
                        'route' => 'app_pages_show',
                        'title' => 'Лікарняний ліфт для медичних закладів',
                    ],
                    [
                        'alias' => 'avto',
                        'path' => '/pages/avto',
                        'route' => 'app_pages_show',
                        'title' => 'Автомобільний ліфт',
                    ],
                    [
                        'alias' => 'low',
                        'path' => '/pages/low-weight',
                        'route' => 'app_pages_show',
                        'title' => 'Маловантажний ліфт',
                    ],
                ]
            ],
            'low-mobile' => [
                'title' => 'Підйомники для інвалідів',
                'route' => 'app_pages_show',
                'path' => '/pages/low-mobile',
            ],
            'platform' => [
                'title' => 'Підйомні платформи',
                'route' => 'app_pages_show',
                'path' => '/pages/platform',
            ],
            'escalators' => [
                'title' => 'Ескалатори',
                'route' => 'app_pages_show',
                'url' => '/pages/escalators',
            ],
            'travolators' => [
                'title' => 'Траволатори',
                'route' => 'app_pages_show',
                'url' => '/pages/travolators',
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

        $countMessages = $this->messageService->findIncomingCount($user->getUserIdentifier());

        if($countMessages > 0) {
            $this->twig->addGlobal('incoming_unread_count', $countMessages);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ControllerEvent::class => 'onControllerEvent',
        ];
    }
}
