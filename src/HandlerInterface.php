<?php

namespace Basko\Bus;

interface HandlerInterface
{
    /**
     * @param object $command
     * @return mixed
     */
    public function handle($command);
}
