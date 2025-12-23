<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
            'identifier' => ['required', 'array'],
            'identifier.email' => ['nullable', 'email', 'required_without:identifier.phone'],
            'identifier.phone' => ['nullable', 'string', 'required_without:identifier.email'],

            // Code: required, exactly 4 digits
            'code' => 'required|string|size:4|regex:/^\d{4}$/',

            // New password: required, minimum 8 characters, must contain:
            // - at least one lowercase letter
            // - at least one uppercase letter
            // - at least one digit
            // - at least one special character
            'new_password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/', // Lowercase letter
                'regex:/[A-Z]/', // Uppercase letter
                'regex:/[0-9]/', // Digit
                'regex:/[@$!%*#?&]/', // Special character
            ],

            // New password confirmation: must match new_password field
            'new_password_confirmation' => 'required|same:new_password',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'identifier.required' => 'Email or phone number is required.',
            'code.required' => 'Reset code is required.',
            'code.size' => 'Reset code must be exactly 4 digits.',
            'code.regex' => 'Reset code must contain only digits.',
            'new_password.required' => 'New password is required.',
            'new_password.min' => 'New password must be at least 8 characters.',
            'new_password.regex' => 'New password must contain uppercase, lowercase, number, and special character.',
            'new_password_confirmation.same' => 'Password confirmation does not match.',
        ];
    }
}
