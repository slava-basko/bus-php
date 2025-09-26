<?php

namespace Basko\Bus\Middleware;

use Basko\Bus\Exception\LockException;
use Basko\Bus\Middleware;

final class LockingMiddleware implements Middleware
{
    /**
     * @var bool
     */
    private $isExecuting;

    /**
     * @param object $command
     * @param callable $next
     * @return mixed
     * @throws \Basko\Bus\Exception\LockException
     */
    public function execute($command, callable $next)
    {
        if ($this->isExecuting) {
            throw new LockException('Command Bus locked because executing another command');
        }

        $this->isExecuting = true;

        try {
            $result = $next($command);
        } finally {
            $this->isExecuting = false;
        }


        return $result;
    }
}
