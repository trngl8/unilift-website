<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

#[AsEventListener]
class UserLoginSuccessListener
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function __invoke(LoginSuccessEvent $event): void
    {
        $user = $event->getUser();

        $roles = $user->getRoles();

        if(in_array("ROLE_USER", $roles)) {
            $event->setResponse(new RedirectResponse($this->router->generate('app_index'))); // TODO: maybe app_user?
        }

        if(in_array("ROLE_ADMIN", $roles)) {
            $event->setResponse(new RedirectResponse($this->router->generate('admin')));
        }
    }
}
