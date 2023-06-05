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

        $session = $event->getRequest()->getSession();

        if(in_array("ROLE_USER", $roles)) {
            // TODO: choose flash type and message
            $session->getFlashBag()->add('notice', 'You are logged in!');
            $event->setResponse(new RedirectResponse($this->router->generate('app_index'))); // TODO: maybe app_user?
        }

        if(in_array("ROLE_ADMIN", $roles)) {
            // TODO: choose flash type and message
            $session->getFlashBag()->add('notice', 'You are logged in!');
            $event->setResponse(new RedirectResponse($this->router->generate('admin')));
        }
    }
}
