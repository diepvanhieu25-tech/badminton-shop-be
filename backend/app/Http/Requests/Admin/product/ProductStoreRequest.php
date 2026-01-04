<?php

namespace App\Http\Requests\Admin\product;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:50', 'unique:products,sku'],
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'original_price' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive,draft'],
            'is_featured' => ['boolean'],
            
            // Validate Ảnh
            'thumbnail' => ['nullable', 'image', 'max:2048'], // 2MB
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['image', 'max:2048'],

            // Validate Biến thể (Quan trọng)
            'has_variants' => ['boolean'],
            'variants' => ['nullable', 'array', 'required_if:has_variants,1'],
            'variants.*.sku' => ['required_if:has_variants,1', 'string', 'distinct'],
            'variants.*.price' => ['required_if:has_variants,1', 'numeric', 'min:0'],
            'variants.*.stock_qty' => ['required_if:has_variants,1', 'integer', 'min:0'],
            'variants.*.attributes.size' => ['nullable', 'string'],
            'variants.*.attributes.color' => ['nullable', 'string'],
        ];
    }
}