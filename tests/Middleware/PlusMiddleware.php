<?php

namespace Basko\Bus\Middleware;

class PlusMiddleware implements \Basko\Bus\Middleware
{
    private $n;

    public function __construct($n)
    {
        $this->n = $n;
    }

    /**
     * @param \Basko\Bus\Command\PlusCommand $command
     * @param callable $next
     * @return void
     */
    public function execute($command, callable $next)
    {
        $command->n = $command->n + $this->n;

        return $next($command);
    }
}