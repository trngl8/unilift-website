<?php

namespace App\Tests\Unit\EventListener;

use App\Entity\User;
use App\EventListener\UserLoginSuccessListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class UserLoginSuccessListenerTest extends TestCase
{
    private EventDispatcherInterface $dispatcher;

    public function setUp(): void
    {
        $this->dispatcher = new EventDispatcher();
    }

    /**
     * @dataProvider dataRedirects
     */
    public function testRoleUserRedirects(string $role, string $route): void
    {
        $router = $this->createMock(RouterInterface::class);
        $authenticator = $this->createMock(AuthenticatorInterface::class);
        $passport = $this->createMock(Passport::class);
        $authenticatedToken = $this->createMock(TokenInterface::class);
        $request = $this->createMock(Request::class);

        $user = new User();
        $user->setRoles([sprintf('ROLE_%s', $role)]);
        $passport->method('getUser')->willReturn($user);
        $router->method('generate')->willReturn($route);

        $listener = new UserLoginSuccessListener($router);
        $this->dispatcher->addListener(LoginSuccessEvent::class, $listener);
        $event = new LoginSuccessEvent($authenticator, $passport, $authenticatedToken, $request, null, 'main');

        $this->dispatcher->dispatch($event, LoginSuccessEvent::class);

        $this->assertInstanceOf(Response::class, $event->getResponse());
    }

    public function dataRedirects(): iterable
    {
        yield 'User' => ['ROLE_USER', 'app_index'];
        yield 'Admin' => ['ROLE_ADMIN', 'admin'];
    }
}
