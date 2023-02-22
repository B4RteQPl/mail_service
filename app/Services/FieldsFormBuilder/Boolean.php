<?php

namespace App\Services\FieldsFormBuilder;

class Boolean
{
    protected string $name;
    protected string $trueValue;
    protected string $falseValue;
    protected bool $isRequired;

    public function __construct($name)
    {
        $this->name = $name;
        $this->trueValue = '1';
        $this->falseValue = '0';
        $this->isRequired = false;
    }

    public static function make($name)
    {
        return new self($name);
    }

    public function trueValue(string $value): static
    {
        $this->trueValue = $value;
        return $this;
    }

    public function falseValue(string  $value): static
    {
        $this->falseValue = $value;
        return $this;
    }

    public function isRequired(string  $value): static
    {
        $this->isRequired = $value;
        return $this;
    }
}
