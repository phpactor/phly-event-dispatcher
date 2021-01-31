<?php
/**
 * @see       https://github.com/phly/phly-event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2018-2019 Matthew Weier O'Phinney (https://mwop.net)
 * @license   https://github.com/phly/phly-event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace PhlyTest\EventDispatcher;

use Phly\EventDispatcher\ListenerProvider\PrioritizedListenerProvider;
use PHPUnit\Framework\TestCase;
use SplObserver;

class PrioritizedListenerProviderTest extends TestCase
{
    /** @var PrioritizedListenerProvider */
    protected $listeners;

    public function setUp(): void
    {
        $this->listeners = new PrioritizedListenerProvider();
    }

    public function createListener()
    {
        return function (object $event): void {
        };
    }

    public function testReturnsOnlyListenersForTheGivenEventInPriorityOrder(): void
    {
        $listener1 = $this->createListener();
        $listener2 = $this->createListener();
        $listener3 = $this->createListener();

        $this->listeners->listen(NonExistentEvent::class, $listener1, 100);
        $this->listeners->listen(TestAsset\TestEvent::class, $listener2, -100);
        $this->listeners->listen(SplObserver::class, $listener3, 100);

        $event = new TestAsset\TestEvent();

        foreach ($this->listeners->getListenersForEvent($event) as $listener) {
            $listeners[] = $listener;
        }

        $this->assertSame([
            $listener3,
            $listener2,
        ], $listeners);
    }
}
