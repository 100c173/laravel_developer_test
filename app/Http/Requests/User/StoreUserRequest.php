<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'role' => 'nullable|string|in:user,admin', // Adjust roles as needed
            'is_active' => 'boolean',
             'phone_number' => 'nullable|string|unique:users,phone_number|regex:/^\+?[1-9]\d{1,14}$/',
            'email_verified' => 'boolean',
        ];
    }
    public function messages(): array
    {
        return [
            'password.confirmed' => 'The password confirmation does not match.',
            'password.min' => 'Password must be at least 8 characters.',
            'role.in' => 'Please select a valid role.',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'email_verified' => $this->boolean('email_verified'),
        ]);
    }
}
