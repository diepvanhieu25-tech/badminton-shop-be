<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
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
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => [
                'required',
                'confirmed', // Yêu cầu field password_confirmation
                Password::min(8)
                    ->letters()    // Phải có chữ cái
                    ->mixedCase()  // Phải có cả chữ hoa và thường
                    ->numbers()    // Phải có số
            ],
            'avatar_url' => ['nullable', 'string', 'url'],
        ];
    }

    /**
     * Tùy chỉnh thông báo lỗi (Tiếng Việt)
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'name.max' => 'Họ tên không được vượt quá 255 ký tự.',

            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
            'email.unique' => 'Email này đã được sử dụng bởi tài khoản khác.',

            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            
            // Các message cụ thể cho rule Password::min()->...
            'password.min' => 'Mật khẩu phải có ít nhất :min ký tự.',
            'password.letters' => 'Mật khẩu phải chứa ít nhất một chữ cái.',
            'password.mixed' => 'Mật khẩu phải chứa cả chữ hoa và chữ thường.',
            'password.numbers' => 'Mật khẩu phải chứa ít nhất một con số.',
            
            'avatar_url.url' => 'Đường dẫn ảnh đại diện không hợp lệ (phải là link http/https).',
        ];
    }

    /**
     * Xử lý khi validate thất bại -> Trả về JSON thay vì Redirect
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