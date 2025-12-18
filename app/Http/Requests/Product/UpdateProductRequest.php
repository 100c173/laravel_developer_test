<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
        // Get the product ID from the route parameters
        $productId = $this->route('product')->id ?? null;

        // Based on the 'products' migration schema
        return [
            // All fields are optional for update, but if present, must follow rules
            'title.en' => ['sometimes', 'string', 'max:255'],
            'title.ar' => ['sometimes', 'string', 'max:255'],

            'description.en' => ['sometimes', 'string'],
            'description.ar' => ['sometimes', 'string'],

            'price' => [
                'required',
                'numeric',
                'min:0',
                'max:999999.99',
            ],
        ];
    }

    /**
     * Get custom validation error messages.
     *
     * These messages are written in a clear and user-friendly way
     * to improve UX in both dashboard and API responses.
     */
    public function messages(): array
    {
        return [
            // Title validation messages
            'title.en.string' => 'The English title must be a valid string.',
            'title.en.max' => 'The English title may not be greater than 255 characters.',

            'title.ar.string' => 'The Arabic title must be a valid string.',
            'title.ar.max' => 'The Arabic title may not be greater than 255 characters.',

            // Description validation messages
            'description.en.string' => 'The English description must be a valid string.',
            'description.ar.string' => 'The Arabic description must be a valid string.',

            // Price validation messages
            'price.numeric' => 'The price must be a valid number.',
            'price.min' => 'The price must be greater than zero.',
        ];
    }

}
