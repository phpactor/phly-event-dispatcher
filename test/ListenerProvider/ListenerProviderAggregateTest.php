<?php
/**
 * @see       https://github.com/phly/phly-event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2018-2019 Matthew Weier O'Phinney (https:/mwop.net)
 * @license   https://github.com/phly/phly-event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace PhlyTest\EventDispatcher\ListenerProvider;

use Phly\EventDispatcher\ListenerProvider\ListenerProviderAggregate;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\EventDispatcher\ListenerProviderInterface;
use PhlyTest\EventDispatcher\TestAsset\TestEvent;

class ListenerProviderAggregateTest extends TestCase
{
    use ProphecyTrait;

    public function testAggregateYieldsFromAttachedProviders(): void
    {
        $event = new TestEvent();
        $listener = function (TestEvent $event): void {
        };

        $provider = $this->prophesize(ListenerProviderInterface::class);
        $provider
            ->getListenersForEvent($event)
            ->willReturn([$listener]);

        $aggregate = new ListenerProviderAggregate();
        $aggregate->attach($provider->reveal());

        $listeners = iterator_to_array($aggregate->getListenersForEvent($event));

        $this->assertSame([$listener], $listeners);
    }
}
