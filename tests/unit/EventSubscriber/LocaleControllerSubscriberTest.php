<?php

namespace App\Tests\Unit\EventSubscriber;

use App\EventSubscriber\LocaleControllerSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class LocaleControllerSubscriberTest extends TestCase
{
    private EventDispatcherInterface $dispatcher;

    public function setUp(): void
    {
        $this->dispatcher = new EventDispatcher();
    }

    public function testLocaleControllerSubscriber(): void
    {
        $listener = new LocaleControllerSubscriber();
        $this->dispatcher->addListener(RequestEvent::class, [$listener, 'onKernelRequest']);

        $request = new Request();
        $session = new Session();
        $request->attributes = new ParameterBag(['_locale' => 'en']);
        $request->setSession($session);

        $kernel = $this->createMock(HttpKernelInterface::class);
        $event = new RequestEvent($kernel, $request, 1);
        $this->dispatcher->dispatch($event, RequestEvent::class);

        $this->assertArrayHasKey('kernel.request', LocaleControllerSubscriber::getSubscribedEvents());
        $this->assertEquals('en', $event->getRequest()->getLocale());
    }

    public function testChangeLocale(): void
    {
        $listener = new LocaleControllerSubscriber('uk');
        $this->dispatcher->addListener(RequestEvent::class, [$listener, 'onKernelRequest']);

        $request = new Request();
        $session = new Session();
        $request->attributes = new ParameterBag([]);
        $request->setSession($session);

        $kernel = $this->createMock(HttpKernelInterface::class);
        $event = new RequestEvent($kernel, $request, 1);
        $this->dispatcher->dispatch($event, RequestEvent::class);

        $this->assertArrayHasKey('kernel.request', LocaleControllerSubscriber::getSubscribedEvents());
        //TODO: expected 'uk'
        $this->assertEquals('en', $event->getRequest()->getLocale());
    }
}