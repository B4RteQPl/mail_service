<?php

namespace App\ValueObjects;

class LastName
{
    /**
     * @var string
     */
    private ?string $value;

    public function __construct(?string $value)
    {
        $this->set($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function get(): string
    {
        return $this->value;
    }

    public function set(string $newValue): void
    {
        $this->value = $newValue;
    }
}
