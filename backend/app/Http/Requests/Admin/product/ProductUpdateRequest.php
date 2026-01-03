<?php

namespace App\Http\Requests\Admin\product;

use App\Enums\ProductStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class ProductUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('product')->id; // Lấy ID sản phẩm hiện tại

        return [
            'name'           => ['required', 'string', 'max:255'],
            'category_id'    => ['required', 'exists:categories,id'],
            'brand_id'       => ['nullable', 'exists:brands,id'],
            'description'    => ['nullable', 'string'],
            'status'         => ['required', new Enum(ProductStatus::class)],
            'is_featured'    => ['boolean'],
            'thumbnail'      => ['nullable', 'image', 'max:2048'],
            'gallery.*'      => ['nullable', 'image', 'max:2048'],
            'has_variants'   => ['boolean'],

            // Validate Product Simple
            'sku'            => ['nullable', 'required_if:has_variants,0', Rule::unique('products')->ignore($id)],
            'price'          => ['nullable', 'required_if:has_variants,0', 'numeric', 'min:0'],
            'original_price' => ['nullable', 'numeric', 'min:0'],

            // Validate Variants
            'variants'                 => ['array', 'required_if:has_variants,1'],
            'variants.*.id'            => ['nullable', 'integer'], // ID biến thể cũ để update
            'variants.*.sku'           => ['required_with:variants', 'distinct'], // Cần check unique database phức tạp hơn ở service hoặc bỏ qua strict check ở đây
            'variants.*.price'         => ['required_with:variants', 'numeric', 'min:0'],
            'variants.*.stock_qty'     => ['required_with:variants', 'integer', 'min:0'],
            'variants.*.attributes'    => ['nullable', 'array'],
        ];
    }
}