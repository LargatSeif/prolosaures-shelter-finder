<?php

namespace App\Http\Requests;

use App\Facades\AltitudeValidator;
use Illuminate\Foundation\Http\FormRequest;

class AltitudeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // You can add authorization logic here if needed for the request
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return AltitudeValidator::getRules();
    }

    /**
    * Get the error messages for the defined validation rules.
    *
    * @return array
    */
   public function messages()
   {
       return AltitudeValidator::getMessages();
   }
}
