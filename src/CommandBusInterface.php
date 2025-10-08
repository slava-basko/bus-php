<?php

namespace Basko\Bus;

interface CommandBusInterface
{
    /**
     * Executes the given command and optionally returns a value
     *
     * @param object $command
     * @return mixed
     * @throws \Basko\Bus\Exception\NoHandlerException|\Exception
     */
    public function handle($command);
}