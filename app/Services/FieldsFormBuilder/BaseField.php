<?php

namespace App\Services\FieldsFormBuilder;

class BaseField
{

    private string $type = 'string';
    private string $fieldName;
    private bool $required;
    private array $placeholder = [];

    public function __construct(string $fieldName)
    {
        $this->fieldName = $fieldName;
        $this->required = false;
    }

    public function placeholder(array $placeholder): self
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    public function required(): self
    {
        $this->required = true;

        return $this;
    }
    static public function make($fieldName)
    {
        return new self($fieldName);
    }

    public function toArray()
    {
        return [
            'type' => $this->type,
            'required' => $this->required,
            'placeholder' => $this->placeholder,
        ];
    }

}
