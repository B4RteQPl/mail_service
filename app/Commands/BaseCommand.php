<?php

namespace App\Commands;

use App\Interfaces\Commands\CommandInterface;

abstract class BaseCommand implements CommandInterface
{
    abstract public function execute(array $params): void;
}
