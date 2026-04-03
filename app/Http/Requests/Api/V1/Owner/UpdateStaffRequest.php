<?php

namespace App\Http\Requests\Api\V1\Owner;

use App\Http\Requests\Concerns\ValidatesPasswords;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStaffRequest extends FormRequest
{
    use ValidatesPasswords;

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:200'],
            'email' => ['sometimes', 'email', 'max:255'],
            'password' => $this->optionalPasswordRule(),
            'phone' => ['nullable', 'string', 'max:20'],
            'avatar_url' => ['nullable', 'url'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'experience_years' => ['nullable', 'integer', 'min:0', 'max:50'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
