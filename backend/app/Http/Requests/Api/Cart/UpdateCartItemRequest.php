<?php

namespace App\Http\Requests\Api\Cart;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Số lượng mới muốn cập nhật (phải >= 1)
            'quantity' => ['required', 'integer', 'min:1'],
        ];
    }
}