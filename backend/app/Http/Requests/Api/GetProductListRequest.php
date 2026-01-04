<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class GetProductListRequest extends FormRequest
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
            'keyword'     => 'nullable|string|max:100',
            'category_id' => 'nullable|integer|exists:categories,id',
            'brand_id'    => 'nullable|integer|exists:brands,id',
            'min_price'   => 'nullable|numeric|min:0',
            // gte: Greater than or equal (Lớn hơn hoặc bằng)
            'max_price'   => 'nullable|numeric|gte:min_price',
            'is_featured' => 'nullable|boolean',
            'sort_by'     => 'nullable|in:newest,price_asc,price_desc,name_asc',
            'limit'       => 'nullable|integer|min:1|max:50',
            'page'        => 'nullable|integer|min:1',
        ];
    }

    /**
     * Tùy chỉnh thông báo lỗi (Tiếng Việt)
     */
    public function messages(): array
    {
        return [
            'keyword.string'      => 'Từ khóa phải là chuỗi ký tự.',
            'keyword.max'         => 'Từ khóa không được vượt quá 100 ký tự.',
            
            'category_id.integer' => 'ID danh mục phải là số nguyên.',
            'category_id.exists'  => 'Danh mục không tồn tại.',
            
            'brand_id.integer'    => 'ID thương hiệu phải là số nguyên.',
            'brand_id.exists'     => 'Thương hiệu không tồn tại.',
            
            'min_price.numeric'   => 'Giá thấp nhất phải là số.',
            'min_price.min'       => 'Giá thấp nhất không được nhỏ hơn 0.',
            
            'max_price.numeric'   => 'Giá cao nhất phải là số.',
            'max_price.gte'       => 'Giá cao nhất phải lớn hơn hoặc bằng giá thấp nhất.',
            
            'is_featured.boolean' => 'Trường nổi bật phải là true hoặc false.',
            
            'sort_by.in'          => 'Kiểu sắp xếp không hợp lệ.',
            
            'limit.integer'       => 'Giới hạn hiển thị phải là số nguyên.',
            'limit.max'           => 'Chỉ được lấy tối đa 50 sản phẩm mỗi trang.',
        ];
    }

    /**
     * Xử lý khi validate thất bại -> Trả về JSON chuẩn
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'Dữ liệu lọc sản phẩm không hợp lệ.',
            'errors' => $validator->errors()
        ], 422));
    }
}