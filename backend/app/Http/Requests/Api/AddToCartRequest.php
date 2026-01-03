<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Phải là ID tồn tại trong bảng biến thể
            'product_variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            // Số lượng phải >= 1
            'quantity'           => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_variant_id.exists' => 'Sản phẩm không tồn tại.',
            'quantity.min'              => 'Số lượng phải ít nhất là 1.',
        ];
    }
}