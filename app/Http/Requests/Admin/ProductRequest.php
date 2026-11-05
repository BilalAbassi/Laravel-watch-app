<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $productId = $this->route('product')?->id ?? $this->route('product');

        return [
            'title' => ['required', 'string', 'max:255'],
            'brand_id' => ['required', 'integer', 'exists:brands,id'],
            'category_ids' => ['required', 'array', 'min:1'],
            'category_ids.*' => ['integer', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'sale_price' => ['nullable', 'numeric', 'min:0.01', 'lt:price'],
            'stock' => ['required', 'integer', 'min:0', 'max:99999'],
            'sku' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('products', 'sku')->ignore($productId),
            ],
            'movement' => ['nullable', 'string', 'max:255'],
            'case_size' => ['nullable', 'string', 'max:100'],
            'case_material' => ['nullable', 'string', 'max:255'],
            'strap_type' => ['nullable', 'string', 'max:255'],
            'glass' => ['nullable', 'string', 'max:255'],
            'water_resistance' => ['nullable', 'string', 'max:255'],
            'warranty' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'string', 'in:Men\'s,Women\'s,Unisex'],
            'badge' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:5000'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,webp,gif', 'max:5120'],
            'primary_image_id' => ['nullable', 'integer'],
            'delete_image_ids' => ['nullable', 'array'],
            'delete_image_ids.*' => ['integer', 'exists:product_images,id'],
            'specs' => ['nullable', 'array'],
            'specs.*' => ['nullable', 'string', 'max:255'],
            'custom_specs' => ['nullable', 'array'],
            'custom_specs.*.name' => ['nullable', 'string', 'max:100'],
            'custom_specs.*.value' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Custom friendly validation error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Please enter a watch title.',
            'brand_id.required' => 'Please select a brand for this watch.',
            'brand_id.exists' => 'The selected brand is invalid.',
            'category_ids.required' => 'Please select at least one watch category.',
            'category_ids.min' => 'Please select at least one category tag.',
            'price.required' => 'Please provide the retail price.',
            'sale_price.lt' => 'The discounted sale price must be lower than the retail price.',
            'stock.required' => 'Please enter available stock units.',
            'images.*.image' => 'Uploaded files must be valid image files (JPG, PNG, WEBP).',
            'images.*.max' => 'Each image must not exceed 5MB.',
        ];
    }
}
