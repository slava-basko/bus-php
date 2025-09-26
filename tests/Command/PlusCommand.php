<?php

namespace Basko\Bus\Command;

class PlusCommand
{
    public $n;

    public function __construct($n)
    {
        $this->n = $n;
    }
}