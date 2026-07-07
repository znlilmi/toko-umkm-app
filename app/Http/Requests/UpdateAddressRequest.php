<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'recipient_name' => ['sometimes', 'required', 'string', 'max:255'],
            'phone'          => ['sometimes', 'required', 'string', 'max:20'],
            'address_line'   => ['sometimes', 'required', 'string'],
            'city_id'        => ['sometimes', 'required', 'integer'],
            'is_default'     => ['sometimes', 'boolean'],
        ];
    }
}
