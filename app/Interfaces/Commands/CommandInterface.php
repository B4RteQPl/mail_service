<?php

namespace App\Interfaces\Commands;

interface CommandInterface
{
    public function execute(array $params): void;
}
