<?php
/**
 * @see       https://github.com/phly/phly-event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2018 Matthew Weier O'Phinney (https:/mwop.net)
 * @license   https://github.com/phly/phly-event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace PhlyTest\EventDispatcher;

use Phly\EventDispatcher\LazyListener;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;

use function Phly\EventDispatcher\lazyListener;

class LazyListenerFunctionTest extends TestCase
{
    use ProphecyTrait;
    public function testFunctionReturnsALazyListenerUsingProvidedArguments(): void
    {
        $container = $this->prophesize(ContainerInterface::class)->reveal();
        $listener = lazyListener($container, TestAsset\Listener::class, 'onTest');

        $this->assertInstanceOf(LazyListener::class, $listener);
    }
}
