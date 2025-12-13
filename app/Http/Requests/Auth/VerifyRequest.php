<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Identifier: required, can be email or phone number
            'identifier' => 'required|array',

            // Code: required, exactly 4 digits
            'code' => 'required|string|size:4|regex:/^\d{4}$/',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'identifier.required' => 'Email or phone number is required.',
            'code.required' => 'Verification code is required.',
            'code.size' => 'Verification code must be exactly 4 digits.',
            'code.regex' => 'Verification code must contain only digits.',
        ];
    }

}
