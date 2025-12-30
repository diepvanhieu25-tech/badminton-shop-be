<?php

namespace App\Http\Requests\Admin\brand;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BrandStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'logo_url'    => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'is_active'   => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('name')) {
            // trim để tránh dấu cách đầu/cuối
            $this->merge(['name' => trim((string) $this->input('name'))]);
        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Dữ liệu không hợp lệ.',
            'errors'  => $validator->errors(),
        ], 422));
    }
}
