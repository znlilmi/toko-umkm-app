<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_method'   => ['required', 'string', 'max:100'],
            'proof_of_payment' => ['required', 'image', 'max:2048'],
        ];
    }
}
