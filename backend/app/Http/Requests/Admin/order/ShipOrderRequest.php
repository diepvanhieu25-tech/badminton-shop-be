<?php

namespace App\Http\Requests\Admin\order;

use Illuminate\Foundation\Http\FormRequest;

class ShipOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'carrier'       => ['required', 'string', 'max:100'], // VD: Viettel Post
            'tracking_code' => ['required', 'string', 'max:50'],  // Mã vận đơn
            'note'          => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'carrier.required'       => 'Vui lòng chọn đơn vị vận chuyển.',
            'tracking_code.required' => 'Vui lòng nhập mã vận đơn.',
        ];
    }
}