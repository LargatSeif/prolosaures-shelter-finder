<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Contracts\Validation\Validator validate(array $data)
 *
 * @see \App\Validators\AltitudeValidator
 */
class AltitudeValidator extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return \App\Validators\AltitudeValidator::class;
    }
}
