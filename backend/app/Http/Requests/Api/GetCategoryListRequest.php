<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class GetCategoryListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'parent_id' => 'nullable|integer|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'parent_id.integer' => 'ID danh mục cha phải là một số nguyên.',
            'parent_id.exists'  => 'Danh mục cha được chọn không tồn tại trong hệ thống.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'Dữ liệu đầu vào không hợp lệ.',
            'errors' => $validator->errors()
        ], 422));
    }
}