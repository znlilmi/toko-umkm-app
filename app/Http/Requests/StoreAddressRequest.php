<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone'          => ['required', 'string', 'max:20'],
            'address_line'   => ['required', 'string'],
            'city_id'        => ['required', 'integer'],
            'is_default'     => ['sometimes', 'boolean'],
        ];
    }
}
