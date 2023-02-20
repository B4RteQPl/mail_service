<?php

namespace App\Services\FieldsFormBuilder;

class FieldBoolean extends Boolean
{
    public static function make($field)
    {
        return [
            'type' => 'boolean',
            'required' => $field->required,
            'trueValue' => $field->true_value,
            'falseValue' => $field->false_value,
        ];
    }
}
