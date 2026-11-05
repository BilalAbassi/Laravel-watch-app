<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    /**
     * Both signed-in customers and guests can place orders.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for Pakistani & international watch orders.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:255'],
            'apartment' => ['nullable', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:100'],
            'delivery_notes' => ['nullable', 'string', 'max:500'],
            'shipping_method' => ['nullable', 'string', 'in:armored,concierge'],
            'payment_method' => ['nullable', 'string', 'max:20'],
            'card_number' => ['nullable', 'string', 'max:25'],
            'engraving' => ['nullable', 'string', 'max:30'],
            'wrist_size' => ['nullable', 'string', 'max:20'],
            'product_id' => ['nullable', 'integer'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
            'variant' => ['nullable', 'string', 'max:50'],
            'promo_code' => ['nullable', 'string', 'max:30'],
            'cart_items_json' => ['nullable', 'string'],
        ];
    }
}
