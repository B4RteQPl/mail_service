<?php

namespace App\Services\FieldsFormBuilder;

class Text extends BaseField
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

//        return [
//            'type' => 'string',
//            'required' => $field->required,
//            'placeholder' => [
//                'pl' => $field->label,
//                'en' => $field->label,
//            ],
//        ];
    }
}
