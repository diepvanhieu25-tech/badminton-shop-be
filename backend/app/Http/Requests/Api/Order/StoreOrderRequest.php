<?php

namespace App\Http\Requests\Api\Order;

use App\Enums\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'receiver_name'    => ['required', 'string', 'max:255'],
            // Regex cho số điện thoại VN cơ bản
            'receiver_phone'   => ['required', 'string', 'regex:/^(0)[0-9]{9}$/'],
            'shipping_address' => ['required', 'string', 'max:500'],
            'note'             => ['nullable', 'string', 'max:1000'],
            // Validate: Giá trị gửi lên phải nằm trong Enum PaymentMethod
            'payment_method'   => ['required', Rule::enum(PaymentMethod::class)], 
        ];
    }

    public function messages(): array
    {
        return [
            'payment_method.enum' => 'Phương thức thanh toán không hợp lệ.',
        ];
    }
}