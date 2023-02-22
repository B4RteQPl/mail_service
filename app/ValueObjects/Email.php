<?php

namespace App\ValueObjects;

use App\Exceptions\ValueObjects\EmailInvalidException;

class Email
{
    /**
     * @var string
     */
    private string $value;

    /**
     * @param string $value
     * @throws EmailInvalidException
     */
    public function __construct(string $value)
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

    /**
     * @throws EmailInvalidException
     */
    public function set(string $newValue): void
    {
        $this->assertEmail($newValue);

        $this->value = $newValue;
    }

    /**
     * @throws EmailInvalidException
     */
    private function assertEmail(string $value): void
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new EmailInvalidException(['email' => $value]);
        }
    }
}
