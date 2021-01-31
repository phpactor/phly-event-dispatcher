<?php
/**
 * @see       https://github.com/phly/phly-event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2018-2019 Matthew Weier O'Phinney (https:/mwop.net)
 * @license   https://github.com/phly/phly-event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace PhlyTest\EventDispatcher\ListenerProvider;

use InvalidArgumentException;
use Phly\EventDispatcher\ListenerProvider\ReflectionBasedListenerProvider;
use PhlyTest\EventDispatcher\TestAsset\TestEvent;
use PhlyTest\EventDispatcher\TestAsset\Listener;
use PHPUnit\Framework\TestCase;

class ReflectionBasedListenerProviderTest extends TestCase
{
    public function testCanAttachWithExplicitType(): void
    {
        $listener = function ($event): void {
        };
        $provider = new ReflectionBasedListenerProvider();
        $provider->listen($listener, TestEvent::class);

        $listeners = iterator_to_array($provider->getListenersForEvent(new TestEvent()));

        $this->assertSame([$listener], $listeners);
    }

    public function testProviderDetectsTypeFromClosure(): void
    {
        $listener = function (TestEvent $event): void {
        };
        $provider = new ReflectionBasedListenerProvider();
        $provider->listen($listener);

        $listeners = iterator_to_array($provider->getListenersForEvent(new TestEvent()));

        $this->assertSame([$listener], $listeners);
    }

    public function testProviderDetectsTypeFromFunctionName(): void
    {
        $provider = new ReflectionBasedListenerProvider();
        $provider->listen('PhlyTest\EventDispatcher\TestAsset\listenerFunction');

        $listeners = iterator_to_array($provider->getListenersForEvent(new TestEvent()));

        $this->assertSame(['PhlyTest\EventDispatcher\TestAsset\listenerFunction'], $listeners);
    }

    public function testProviderDetectsTypeFromStaticMethodName(): void
    {
        $listener = Listener::class . '::onStatic';
        $provider = new ReflectionBasedListenerProvider();
        $provider->listen($listener);

        $listeners = iterator_to_array($provider->getListenersForEvent(new TestEvent()));

        $this->assertSame([$listener], $listeners);
    }

    public function testProviderDetectsTypeFromArrayStaticMethod(): void
    {
        $listener = [Listener::class, 'onStatic'];
        $provider = new ReflectionBasedListenerProvider();
        $provider->listen($listener);

        $listeners = iterator_to_array($provider->getListenersForEvent(new TestEvent()));

        $this->assertSame([$listener], $listeners);
    }

    public function testProviderDetectsTypeFromArrayInstanceMethod(): void
    {
        $instance = new Listener();
        $listener = [$instance, 'onTest'];
        $provider = new ReflectionBasedListenerProvider();
        $provider->listen($listener);

        $listeners = iterator_to_array($provider->getListenersForEvent(new TestEvent()));

        $this->assertSame([$listener], $listeners);
    }

    public function testProviderDetectsTypeFromInvokableInstance(): void
    {
        $listener = new Listener();
        $provider = new ReflectionBasedListenerProvider();
        $provider->listen($listener);

        $listeners = iterator_to_array($provider->getListenersForEvent(new TestEvent()));

        $this->assertSame([$listener], $listeners);
    }

    public function testListenRaisesExceptionIfUnableToDetermineEventType(): void
    {
        $listener = function ($event): void {
        };
        $provider = new ReflectionBasedListenerProvider();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing event parameter for listener');
        $provider->listen($listener);
    }
}
