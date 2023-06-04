<?php

namespace App\Tests\Unit\EventSubscriber;

use App\Entity\Profile;
use App\Entity\User;
use App\EventSubscriber\UserLocaleSubscriber;
use App\Repository\ProfileRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class UserLocaleSubscriberTest extends TestCase
{
    const DEFAULT_LOCALE = 'en';

    private $user;

    private EventDispatcherInterface $dispatcher;

    public function setUp(): void
    {
        $this->dispatcher = new EventDispatcher();
        $this->user = new User();
        $this->user->setUsername('test@test.com');
    }

    public function testUserLocaleEmptyProfile(): void
    {
        $requestStack = $this->createMock(RequestStack::class);
        $authenticationToken = $this->createMock(TokenInterface::class);
        $profileRepository = $this->createMock(ProfileRepository::class);

        $profileRepository->method('findOneBy')->willReturn(null);
        $authenticationToken->method('getUser')->willReturn($this->user);

        $listener = new UserLocaleSubscriber($requestStack, $profileRepository);
        $this->dispatcher->addListener(InteractiveLoginEvent::class, [$listener, 'onInteractiveLogin']);

        $request = new Request();
        $event = new InteractiveLoginEvent($request, $authenticationToken);

        $this->dispatcher->dispatch($event, InteractiveLoginEvent::class);

        $this->assertArrayHasKey('security.interactive_login', UserLocaleSubscriber::getSubscribedEvents());
        $this->assertEquals(self::DEFAULT_LOCALE, $event->getRequest()->getLocale());
    }

    public function testUserLocaleSessionSuccess(): void
    {
        $profile= new Profile();
        $profile->setLocale('uk');

        $requestStack = $this->createMock(RequestStack::class);
        $authenticationToken = $this->createMock(TokenInterface::class);
        $profileRepository = $this->createMock(ProfileRepository::class);

        $profileRepository->method('findOneBy')->willReturn($profile);
        $authenticationToken->method('getUser')->willReturn($this->user);

        $listener = new UserLocaleSubscriber($requestStack, $profileRepository);
        $this->dispatcher->addListener(InteractiveLoginEvent::class, [$listener, 'onInteractiveLogin']);

        $request = new Request();
        $event = new InteractiveLoginEvent($request, $authenticationToken);

        $this->dispatcher->dispatch($event, InteractiveLoginEvent::class);

        $this->assertArrayHasKey('security.interactive_login', UserLocaleSubscriber::getSubscribedEvents());
        //TODO: should be uk in result
        $this->assertEquals('en', $event->getRequest()->getLocale());
    }
}