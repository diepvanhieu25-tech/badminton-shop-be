<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class GetCategoryListRequest extends FormRequest
{
    /**
     * Xác định xem user có quyền thực hiện request này không.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Các luật (rules) validate dữ liệu
     */
    public function rules(): array
    {
        return [
            // Kiểm tra parent_id: có thể null (lấy danh mục gốc), phải là số, và phải tồn tại trong bảng categories
            'parent_id' => 'nullable|integer|exists:categories,id',
        ];
    }

    /**
     * Tùy chỉnh thông báo lỗi (Tiếng Việt)
     */
    public function messages(): array
    {
        return [
            'parent_id.integer' => 'ID danh mục cha phải là một số nguyên.',
            'parent_id.exists'  => 'Danh mục cha được chọn không tồn tại trong hệ thống.',
        ];
    }

    /**
     * Xử lý khi validate thất bại -> Trả về JSON chuẩn
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'Dữ liệu đầu vào không hợp lệ.',
            'errors' => $validator->errors()
        ], 422));
    }
}