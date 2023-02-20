<?php

namespace App\Services\ExternalServices;

abstract class AbstractServiceLoader
{
    protected array $config = [];

    public function __construct()
    {
        $this->init();
    }

    public function init(): void
    {
        foreach (static::ACTIVE_SERVICES as $serviceClass => $keyName) {
            if (class_exists($serviceClass)) {
                $this->config[$keyName] = $serviceClass;
            }
        }
    }

    public function __get(string $name)
    {
        $class = $this->config[$name] ?? null;
        if ($class) {
            return new $class();
        } else {
            return null;
        }
    }
}
