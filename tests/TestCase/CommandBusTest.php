<?php

namespace Basko\Bus\TestCase;

use Basko\Bus\Command\PlusCommand;
use Basko\Bus\CommandBus;
use Basko\Bus\Exception\LockException;
use Basko\Bus\Exception\NoHandlerException;
use Basko\Bus\Handler\PlusCommandHandler;
use Basko\Bus\Middleware\LockingMiddleware;
use PHPUnit\Framework\TestCase;

class CommandBusTest extends TestCase
{
    public function testCommandBus()
    {
        $bus = CommandBus::create([
            PlusCommand::class => new PlusCommandHandler(),
        ]);

        $result = $bus->handle(new PlusCommand(1));

        $this->assertEquals(2, $result);
    }

    public function testRunUnknownCommand()
    {
        $bus = CommandBus::create([]);

        try {
            $bus->handle(new \stdClass());
        } catch (NoHandlerException $e) {
            $this->assertEquals(
                "Command stdClass doesn't have a handler",
                $e->getMessage()
            );
        }
    }

    public function testLock()
    {
        $lockingMiddleware = new LockingMiddleware();

        $fn = function () use (&$secondCommand, $lockingMiddleware) {
            $lockingMiddleware->execute(new PlusCommand(1), function () {
                return null;
            });
        };

        try {
            $lockingMiddleware->execute(new PlusCommand(1), $fn);
        } catch (LockException $lockException) {
            $this->assertEquals(
                'Command Bus locked because executing another command',
                $lockException->getMessage()
            );
        }
    }
}