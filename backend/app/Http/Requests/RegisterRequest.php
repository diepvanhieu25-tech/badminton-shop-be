<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password; // Import class Password

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            
            // unique:users -> Tự động check trùng email trong bảng users
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            
            // Sử dụng Password Rule object để bảo mật hơn
            'password' => [
                'required',
                'confirmed', // Bắt buộc phải có field password_confirmation khớp
                Password::min(6)
                    ->letters()       // Phải có ít nhất 1 chữ cái
                    ->mixedCase()     // (Tuỳ chọn) Phải có chữ hoa và thường
                    ->numbers()       // (Tuỳ chọn) Phải có số
                    // ->uncompromised() // (Tuỳ chọn) Check xem pass có bị lộ trên mạng chưa
            ],
            
            'phone' => ['nullable', 'string', 'max:15'],
        ];
    }

    // Giữ nguyên logic trả về JSON custom của bạn
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Dữ liệu không hợp lệ',
            'errors' => $validator->errors()
        ], 422));
    }
    
    // (Tuỳ chọn) Custom thông báo lỗi tiếng Việt nếu cần
    public function messages(): array
    {
        return [
            'email.unique' => 'Email này đã được đăng ký tài khoản khác.',
            'password.confirmed' => 'Mật khẩu nhập lại không khớp.',
            'password.min' => 'Mật khẩu phải có ít nhất :min ký tự.',
        ];
    }
}