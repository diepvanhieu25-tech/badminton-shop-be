<?php

namespace App\Http\Requests\Api\Cart;

use Illuminate\Foundation\Http\FormRequest;

class SelectCartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Danh sách ID của cart_items muốn thay đổi trạng thái
            'item_ids'   => ['required', 'array'],
            'item_ids.*' => ['integer', 'exists:cart_items,id'],
            // Trạng thái muốn set (true: chọn, false: bỏ chọn)
            'selected'   => ['required', 'boolean'],
        ];
    }
}