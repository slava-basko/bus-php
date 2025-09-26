<?php

namespace Basko\Bus;

interface Middleware
{
    /**
     * @param object $command
     * @param callable $next
     * @return mixed
     */
    public function execute($command, callable $next);
}
