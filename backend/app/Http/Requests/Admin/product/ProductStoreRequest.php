<?php

namespace App\Http\Requests\Admin\product;

use App\Enums\ProductStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ProductStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'           => ['required', 'string', 'max:255'],
            'category_id'    => ['required', 'exists:categories,id'],
            'brand_id'       => ['nullable', 'exists:brands,id'],
            'description'    => ['nullable', 'string'],
            'status'         => ['required', new Enum(ProductStatus::class)],
            'is_featured'    => ['boolean'],
            'thumbnail'      => ['nullable', 'image', 'max:2048'], // Ảnh đại diện chính
            'gallery.*'      => ['nullable', 'image', 'max:2048'], // Album ảnh
            'has_variants'   => ['boolean'],

            // === Validate cho sản phẩm đơn (Không có biến thể) ===
            'sku'            => ['nullable', 'required_if:has_variants,0', 'unique:products,sku'],
            'price'          => ['nullable', 'required_if:has_variants,0', 'numeric', 'min:0'],
            'original_price' => ['nullable', 'numeric', 'min:0'],

            // === Validate cho biến thể (Nếu has_variants = 1) ===
            'variants'                 => ['array', 'required_if:has_variants,1'],
            'variants.*.sku'           => ['required_with:variants', 'distinct', 'unique:product_variants,sku'],
            'variants.*.price'         => ['required_with:variants', 'numeric', 'min:0'],
            'variants.*.original_price'=> ['nullable', 'numeric', 'min:0'],
            'variants.*.stock_qty'     => ['required_with:variants', 'integer', 'min:0'],
            // attributes vd: {"color": "Red", "size": "L"}
            'variants.*.attributes'    => ['nullable', 'array'], 
        ];
    }
}