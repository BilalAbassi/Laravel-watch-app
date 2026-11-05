<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SpecAttributeRequest extends FormRequest
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
        $attributeId = $this->route('specification')?->id;

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('spec_attributes', 'name')->ignore($attributeId),
            ],
            'slug' => [
                'nullable',
                'string',
                'max:120',
                Rule::unique('spec_attributes', 'slug')->ignore($attributeId),
            ],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'values' => ['nullable', 'array'],
            'values.*' => ['nullable', 'string', 'max:255'],
            'new_values' => ['nullable', 'array'],
            'new_values.*' => ['nullable', 'string', 'max:255'],
            'delete_value_ids' => ['nullable', 'array'],
            'delete_value_ids.*' => ['integer', 'exists:spec_attribute_values,id'],
        ];
    }
}
