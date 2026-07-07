<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShopRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'slug'        => ['required', 'string', 'max:255', 'unique:shops,slug'],
            'description' => ['nullable', 'string'],
            'logo'        => ['nullable', 'image', 'max:2048'],
            'banner'      => ['nullable', 'image', 'max:4096'],
            'address'     => ['required', 'string'],
            'city_id'     => ['required', 'integer'],
        ];
    }
}
