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
                'route' => 'default_index',
                'path' => '/pages/about',
                'url' => '/index',
            ],
            'lift' => [
                'title' => 'Ліфти',
                'route' => 'app_product_index',
                'params' => ['alias' => 'lift'],
                'url' => '/product',
                'items' => [
                    [
                        'alias' => 'cottage',
                        'path' => '/pages/cottage',
                        'title' => 'Котетджний ліфт',
                    ],
                    [
                        'alias' => 'passenger',
                        'path' => '/pages/passenger',
                        'title' => 'Пасажирський ліфт для ЖК',
                    ],
                    [
                        'alias' => 'healthy',
                        'path' => '/pages/healthy',
                        'title' => 'Лікарняний ліфт для медичних закладів',
                    ],
                    [
                        'alias' => 'avto',
                        'path' => '/pages/avto',
                        'title' => 'Автомобільний ліфт',
                    ],
                    [
                        'alias' => 'low',
                        'path' => '/pages/low-weight',
                        'title' => 'Маловантажний ліфт',
                    ],
                ]
            ],
            'low-mobile' => [
                'title' => 'Підйомники для інвалідів',
                'route' => 'app_project_index',
                'url' => '/project',
            ],
            'platform' => [
                'title' => 'Підйомні платформи',
                'route' => 'app_offer_index',
                'url' => '/offer',
            ],
            'escalators' => [
                'title' => 'Ескалатори',
                'route' => 'topic_index',
                'url' => '/topic',
            ],
            'travolators' => [
                'title' => 'Траволатори',
                'route' => 'topic_index',
                'url' => '/topic',
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
