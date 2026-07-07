<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'         => ['sometimes', 'required', 'string', 'max:255'],
            'slug'         => ['sometimes', 'required', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($this->route('product'))],
            'description'  => ['nullable', 'string'],
            'price'        => ['sometimes', 'required', 'numeric', 'min:0'],
            'stock'        => ['sometimes', 'required', 'integer', 'min:0'],
            'weight'       => ['sometimes', 'required', 'integer', 'min:1'],
            'is_active'    => ['sometimes', 'boolean'],
            'category_ids' => ['sometimes', 'array', 'min:1'],
            'category_ids.*' => ['integer', 'exists:categories,id'],
        ];
    }
}
