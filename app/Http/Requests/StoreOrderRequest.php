<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'address_id'   => ['required', 'integer', 'exists:addresses,id'],
            'courier'      => ['required', 'string', 'max:100'],
            'cart_item_ids' => ['required', 'array', 'min:1'],
            'cart_item_ids.*' => ['integer', 'exists:carts,id'],
        ];
    }
}
