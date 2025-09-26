<?php

namespace Basko\Bus;

use Basko\Bus\Middleware\CommandHandlerMiddleware;
use Basko\Bus\Middleware\LockingMiddleware;

final class CommandBus
{
    /**
     * @var callable(object $command):mixed
     */
    private $chain;

    /**
     * @param array<\Basko\Bus\Middleware> $middlewares
     */
    public function __construct($middlewares)
    {
        $fn = static function () {
            return null; // final, noop
        };

        foreach ($middlewares as $middleware) {
            if (!($middleware instanceof Middleware)) {
                throw new \InvalidArgumentException(\sprintf(
                    "Param \$middlewares of %s must be array of %s",
                    __METHOD__,
                    Middleware::class
                ));
            }

            $fn = static function ($command) use ($fn, $middleware) {
                return $middleware->execute($command, $fn);
            };
        }

        $this->chain = $fn;
    }

    /**
     * @param array<class-string, \Basko\Bus\HandlerInterface> $map
     * @return \Basko\Bus\CommandBus
     */
    public static function create(array $map)
    {
        return new CommandBus([
            new LockingMiddleware(),
            new CommandHandlerMiddleware($map),
        ]);
    }

    /**
     * Executes the given command and optionally returns a value
     *
     * @param object $command
     * @return mixed
     * @throws \Basko\Bus\Exception\NoHandlerException|\Exception
     */
    public function handle($command)
    {
        return \call_user_func($this->chain, $command);
    }
}
