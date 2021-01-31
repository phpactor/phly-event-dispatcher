<?php
/**
 * @see       https://github.com/phly/phly-event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2018-2019 Matthew Weier O'Phinney (https://mwop.net)
 * @license   https://github.com/phly/phly-event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace PhlyTest\EventDispatcher;

use Phly\EventDispatcher\EventDispatcher;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

class EventDispatcherTest extends TestCase
{
    use ProphecyTrait;
    use CommonDispatcherTests;

    public function setUp(): void
    {
        $this->provider = $this->prophesize(ListenerProviderInterface::class);
        $this->dispatcher = new EventDispatcher($this->provider->reveal());
    }

    public function getDispatcher() : EventDispatcherInterface
    {
        return $this->dispatcher;
    }

    public function getListenerProvider() : ObjectProphecy
    {
        return $this->provider;
    }
}
