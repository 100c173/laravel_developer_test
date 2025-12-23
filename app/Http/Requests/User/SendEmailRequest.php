<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class SendEmailRequest extends FormRequest
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
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
            'action_url' => 'nullable|url|max:500',
            'action_text' => 'nullable|string|max:50',
        ];
    }

    public function attributes(): array
    {
        return [
            'action_url' => 'action URL',
            'action_text' => 'action button text',
        ];
    }

    public function messages(): array
    {
        return [
            'message.min' => 'The message must be at least 10 characters.',
            'action_url.url' => 'Please enter a valid URL for the action link.',
        ];
    }
}
