<?php

namespace App\Clients;

class BaseEntity
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function toArray(): array
    {
        return array_filter($this->data, function ($key) {
            return in_array($key, $this->config);
        }, ARRAY_FILTER_USE_KEY);

    }

    public function __get($name)
    {
        return $this->data[$name] ?? null;
    }
}
