<?php

namespace Basko\Bus\Handler;

use Basko\Bus\HandlerInterface;

class PlusCommandHandler implements HandlerInterface
{
    /**
     * @param \Basko\Bus\Command\PlusCommand $command
     * @return void
     */
    public function handle($command)
    {
        return 1 + $command->n;
    }
}