<?php

namespace Basko\Bus\Middleware;

use Basko\Bus\Exception\NoHandlerException;
use Basko\Bus\HandlerInterface;
use Basko\Bus\Middleware;

final class CommandHandlerMiddleware implements Middleware
{
    /**
     * @var array<class-string, \Basko\Bus\HandlerInterface>
     */
    private $map;

    /**
     * @param array<class-string, \Basko\Bus\HandlerInterface> $map
     */
    public function __construct(array $map)
    {
        foreach ($map as $commandClass => $handler) {
            if (!\class_exists($commandClass)) {
                throw new \InvalidArgumentException("Command $commandClass does not exist");
            }

            if (!($handler instanceof HandlerInterface)) {
                throw new \InvalidArgumentException(\sprintf(
                    "Handler for command %s must be an instance of %s",
                    $commandClass,
                    HandlerInterface::class
                ));
            }

            $this->map[$commandClass] = $handler;
        }
    }

    /**
     * @param object $command
     * @param callable $next
     * @return mixed
     * @throws \Basko\Bus\Exception\NoHandlerException
     */
    public function execute($command, callable $next)
    {
        if (!\is_object($command)) {
            throw new \InvalidArgumentException(\sprintf(
                "Param 1 of %s must be an object, %s given",
                __METHOD__,
                \get_class($command)
            ));
        }

        if (!isset($this->map[\get_class($command)])) {
            throw new NoHandlerException(\sprintf(
                "Command %s doesn't have a handler",
                \get_class($command)
            ));
        }

        return $this->map[\get_class($command)]->handle($command);
    }
}
