<?php
/**
 * @see       https://github.com/phly/phly-event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2018-2019 Matthew Weier O'Phinney (https:/mwop.net)
 * @license   https://github.com/phly/phly-event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace PhlyTest\EventDispatcher\ListenerProvider;

use Phly\EventDispatcher\ListenerProvider\RandomizedListenerProvider;
use PhlyTest\EventDispatcher\TestAsset\TestEvent;
use PHPUnit\Framework\TestCase;

class RandomizedListenerProviderTest extends TestCase
{
    public function testRandomizesOrderOfListeners(): void
    {
        $listeners = [];
        for ($i = 0; $i < 10; $i += 1) {
            $listeners[] = function (TestEvent $event): void {
            };
        }

        $provider = new RandomizedListenerProvider();
        foreach ($listeners as $listener) {
            $provider->listen(TestEvent::class, $listener);
        }

        $received = iterator_to_array($provider->getListenersForEvent(new TestEvent()));
        $this->assertNotSame($listeners, $received);
    }
}
