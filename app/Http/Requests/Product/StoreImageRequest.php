<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreImageRequest extends FormRequest
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
            'images' => ['required', 'array', 'min:1', 'max:10'],
            'images.*' => [
                'required',
                'image',
                'mimes:jpeg,jpg,png,gif,webp',
                'max:2048', // 2MB
                'dimensions:min_width=100,min_height=100,max_width=5000,max_height=5000'
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'images.required' => __('Please select at least one image'),
            'images.array' => __('Please select valid images'),
            'images.min' => __('Please select at least one image'),
            'images.max' => __('You can upload maximum 10 images at once'),

            'images.*.required' => __('Each image is required'),
            'images.*.image' => __('Each file must be an image'),
            'images.*.mimes' => __('Allowed image formats: jpeg, jpg, png, gif, webp'),
            'images.*.max' => __('Each image must not exceed 2MB'),
            'images.*.dimensions' => __('Image dimensions must be between 100x100 and 5000x5000 pixels'),
        ];
    }
    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // إذا كان الحقل images هو ملف واحد بدلاً من مصفوفة
        if ($this->hasFile('images') && !is_array($this->file('images'))) {
            $this->merge([
                'images' => [$this->file('images')]
            ]);
        }
    }

}
