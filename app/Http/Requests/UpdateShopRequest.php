<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateShopRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['sometimes', 'required', 'string', 'max:255'],
            'slug'        => ['sometimes', 'required', 'string', 'max:255', Rule::unique('shops', 'slug')->ignore($this->route('shop'))],
            'description' => ['nullable', 'string'],
            'logo'        => ['nullable', 'image', 'max:2048'],
            'banner'      => ['nullable', 'image', 'max:4096'],
            'address'     => ['sometimes', 'required', 'string'],
            'city_id'     => ['sometimes', 'required', 'integer'],
        ];
    }
}
