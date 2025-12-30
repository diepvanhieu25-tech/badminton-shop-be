<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\ProductStatus;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['sometimes', 'exists:categories,id'],
            'brand_id'    => ['nullable', 'exists:brands,id'],
            'name'        => ['required', 'string', 'max:255'],
            // SKU phải là duy nhất, có thể null
            'sku' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('products', 'sku')->ignore($this->product)
            ],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'description' => ['nullable', 'string'],
            'price'       => ['required', 'numeric', 'min:0'],
            'original_price' => ['nullable', 'numeric', 'min:0'],
            // Validate theo Enum
            'status'      => ['nullable', Rule::enum(ProductStatus::class)],
            'has_variants' => ['boolean'],
            'is_featured' => ['boolean'],
        ];
    }
}
