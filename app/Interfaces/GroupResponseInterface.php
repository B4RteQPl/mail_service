<?php

namespace App\Interfaces;

interface GroupResponseInterface
{
    static function convert($response): array;
}
