<?php

namespace App\Services\ExternalServices;

abstract class AbstractCommandLoader
{
    protected array $config = [];

    public function __construct()
    {
        $this->init();
    }

    public function init(): void
    {
        foreach (static::ACTIVE_COMMANDS as $serviceClass => $keyName) {
            if (class_exists($serviceClass)) {
                $this->config[$keyName] = $serviceClass;
            }
        }
    }

    public function __get(string $name)
    {
        $class = $this->config[$name] ?? null;

        if ($name === 'client') {
            return $this->client;
        }

        if ($class) {
            return new $class($this->client);
        } else {
            return null;
        }
    }
}
