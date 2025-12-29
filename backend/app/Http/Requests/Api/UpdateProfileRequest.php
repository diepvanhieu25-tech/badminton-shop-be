<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:15'],
            // Validate ảnh: Phải là ảnh, đuôi jpg/png..., tối đa 2MB
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            // Password: Nếu có gửi lên thì phải validate, không thì thôi
            'password' => ['nullable', 'confirmed', Password::min(6)],
        ];
    }
}