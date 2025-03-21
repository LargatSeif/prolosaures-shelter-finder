<?php

namespace App\Validators;

use Illuminate\Support\Facades\Validator;

class AltitudeValidator
{
    private array $rules = [
        'altitudes' => 'required|array|min:1|max:100000',
        'altitudes.*' => 'required|integer|min:0|max:100000',
    ];
    
    private array $messages = [
        'altitudes.required' => 'Please enter at least one altitude.',
        'altitudes.array' => 'Altitudes must be an array.',
        'altitudes.min' => 'You must enter at least one altitude.',
        'altitudes.max' => 'You cannot enter more than 100000 altitudes.',
        'altitudes.*.required' => 'Each altitude is required.',
        'altitudes.*.integer' => 'Each altitude must be an integer.',
        'altitudes.*.min' => 'Each altitude must be greater than or equal to 0.',
        'altitudes.*.max' => 'Each altitude must be less than or equal to 100000.',
    ];

    /**
     * Validates the given data.
     *
     * @param array $data The data to validate.
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validate(array $data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($data, $this->rules, $this->messages);
    }

    /**
     * Get the validation rules.
     *
     * @return array
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }
}
