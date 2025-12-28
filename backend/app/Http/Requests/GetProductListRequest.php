<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetProductListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => 'nullable|integer|exists:categories,id',
            'brand_id'    => 'nullable|integer|exists:brands,id',
            'keyword'     => 'nullable|string|max:100',
            'min_price'   => 'nullable|numeric|min:0',
            'max_price'   => 'nullable|numeric|gte:min_price',
            'sort_by'     => 'nullable|in:newest,price_asc,price_desc,name_asc',
            'limit'       => 'nullable|integer|min:1|max:50',
        ];
    }
}
