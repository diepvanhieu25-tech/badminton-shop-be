<?php

namespace App\Http\Requests\Admin\product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\ProductStatus;

class ProductFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Nếu route đã có middleware admin thì return true
        return true;
    }

    public function rules(): array
    {
        return [
            // Search theo tên / SKU
            'q' => [
                'nullable',
                'string',
                'max:255',
            ],

            // Filter trạng thái sản phẩm (enum)
            'status' => [
                'nullable',
                Rule::in(
                    array_map(
                        fn ($status) => $status->value,
                        ProductStatus::cases()
                    )
                ),
            ],
        ];
    }
}
