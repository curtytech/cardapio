<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeliveryOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'client_phone' => $this->filled('client_phone')
                ? preg_replace('/\D+/', '', (string) $this->input('client_phone'))
                : null,
            'zipcode' => $this->filled('zipcode')
                ? preg_replace('/\D+/', '', (string) $this->input('zipcode'))
                : null,
            'state' => $this->filled('state')
                ? strtoupper(substr((string) $this->input('state'), 0, 2))
                : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'cart' => ['required', 'array', 'min:1'],
            'cart.*.id' => ['required', 'integer', 'exists:products,id'],
            'cart.*.quantity' => ['required', 'integer', 'min:1'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'client_name' => ['required', 'string', 'max:255'],
            'client_phone' => ['required', 'string', 'max:20'],
            'zipcode' => ['nullable', 'string', 'max:10'],
            'address' => ['required', 'string', 'max:255'],
            'number' => ['nullable', 'string', 'max:50'],
            'neighborhood' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'size:2'],
            'complement' => ['nullable', 'string', 'max:255'],
            'reference' => ['nullable', 'string', 'max:255'],
            'payment_method' => ['nullable', 'string', 'max:100'],
            'observation' => ['nullable', 'string'],
        ];
    }
}
