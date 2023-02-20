<?php

namespace App\Services\FieldsFormBuilder;

class BaseField
{

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

    public function toArray()
    {
        return [
            'type' => 'string',
            'required' => $this->required,
            'placeholder' => [
                'pl' => 'Wybierz ' . $this->fieldName,
                'en' => 'Pick ' . $this->fieldName,
            ],
        ];
    }

    static public function make($field)
    {
        return new self($field);
    }
}
