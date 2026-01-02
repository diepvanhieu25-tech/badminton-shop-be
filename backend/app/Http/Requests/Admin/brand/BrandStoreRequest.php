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
        // Xử lý checkbox: nếu không tick, HTML không gửi value lên -> mặc định set 0
        $this->merge([
            'is_active' => $this->has('is_active') ? 1 : 0,
            'name'      => $this->has('name') ? trim((string) $this->input('name')) : null,
        ]);
    }
}
