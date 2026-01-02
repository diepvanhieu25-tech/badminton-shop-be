<?php

namespace App\Http\Requests\Admin\order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:255'],

            'status' => [
                'nullable',
                'string',
                Rule::in([
                    'pending',
                    'confirmed',
                    'shipping',
                    'completed',
                    'cancelled',
                    'returned',
                ]),
            ],

            'sort' => [
                'nullable',
                'string',
              Rule::in([
                'date_desc',
                'date_asc',
                'total_asc',
                'total_desc',
            ]),
            ],
        ];
    }
}