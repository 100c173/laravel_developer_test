<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            // First name: required, string, max 255 characters
            'first_name' => 'required|string|max:255',

            // Last name: required, string, max 255 characters
            'last_name' => 'required|string|max:255',

            // Email: optional but must be unique if provided, valid email format
            'email' => 'nullable|email|unique:users,email',

            // Country: required, must exist in countries table
            'country_id' => 'required|exists:countries,id',

            // City: required, must exist in cities table
            'city_id' => 'required|exists:cities,id',

            // Phone number: optional but must be unique if provided, valid phone format
            'phone_number' => 'nullable|string|unique:users,phone_number|regex:/^\+?[1-9]\d{1,14}$/',

            // Password: required, minimum 8 characters, must contain:
            // - at least one lowercase letter
            // - at least one uppercase letter
            // - at least one digit
            // - at least one special character
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/', // Lowercase letter
                'regex:/[A-Z]/', // Uppercase letter
                'regex:/[0-9]/', // Digit
                'regex:/[@$!%*#?&]/', // Special character
            ],

            // Password confirmation: must match password field
            'password_confirmation' => 'required|same:password',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'email.unique' => 'This email is already registered.',
            'email.email' => 'Please provide a valid email address.',
            'country_id.required' => 'Please select a country.',
            'country_id.exists' => 'The selected country is invalid.',
            'city_id.required' => 'Please select a city.',
            'city_id.exists' => 'The selected city is invalid.',
            'phone_number.unique' => 'This phone number is already registered.',
            'phone_number.regex' => 'Please provide a valid phone number.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.regex' => 'Password must contain uppercase, lowercase, number, and special character.',
            'password_confirmation.same' => 'Password confirmation does not match.',
        ];
    }
}
