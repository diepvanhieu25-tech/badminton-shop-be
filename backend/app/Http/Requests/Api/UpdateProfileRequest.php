<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Xác định xem user có quyền thực hiện request này không.
     */
    public function authorize(): bool
    {
        // Thông thường update profile yêu cầu user phải đăng nhập
        return true; 
    }

    /**
     * Các luật (rules) validate dữ liệu
     */
    public function rules(): array
    {
        // Lấy ID user đang đăng nhập để dùng cho rule unique->ignore
        $userId = $this->user()->id;

        return [
            // 'sometimes': Chỉ validate nếu field này có được gửi lên
            'name' => ['sometimes', 'string', 'max:255'],
            
            'phone' => [
                'sometimes',
                'nullable',
                'string',
                'max:20',
                Rule::unique('users', 'phone')->ignore($userId),
            ],
            
            // Validate file ảnh upload
            'avatar' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Max 2MB
            
            'password' => [
                'sometimes',
                'nullable',
                'confirmed', // Yêu cầu password_confirmation
                Password::min(8)->letters()->mixedCase()->numbers(),
            ],
        ];
    }

    /**
     * Tùy chỉnh thông báo lỗi (Tiếng Việt)
     */
    public function messages(): array
    {
        return [
            'name.string' => 'Tên phải là một chuỗi ký tự.',
            'name.max' => 'Tên không được vượt quá 255 ký tự.',

            'phone.unique' => 'Số điện thoại này đã được sử dụng bởi tài khoản khác.',
            'phone.max' => 'Số điện thoại quá dài.',

            // Các lỗi liên quan đến file ảnh
            'avatar.image' => 'File tải lên phải là hình ảnh.',
            'avatar.mimes' => 'Ảnh phải có định dạng: jpeg, png, jpg, hoặc gif.',
            'avatar.max' => 'Dung lượng ảnh không được vượt quá 2MB.',

            // Các lỗi mật khẩu
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'password.min' => 'Mật khẩu mới phải có ít nhất :min ký tự.',
            'password.mixed' => 'Mật khẩu phải chứa cả chữ hoa và thường.',
            'password.numbers' => 'Mật khẩu phải chứa ít nhất một số.',
        ];
    }

    /**
     * Xử lý khi validate thất bại -> Trả về JSON chuẩn
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'Dữ liệu cập nhật không hợp lệ.',
            'errors' => $validator->errors()
        ], 422));
    }
}