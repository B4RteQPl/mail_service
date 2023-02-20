<?php

namespace App\Services\FieldsFormBuilder;

class Email
{
    public function make($field)
    {
        return [
            'type' => 'string',
            'required' => true,
            'placeholder' => [
                'pl' => $field->label,
                'en' => $field->label,
            ],
        ];
    }
}
