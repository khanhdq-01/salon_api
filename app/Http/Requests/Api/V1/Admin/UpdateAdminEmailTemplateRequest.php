<?php

namespace App\Http\Requests\Api\V1\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdminEmailTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'template_name' => ['sometimes', 'string', 'max:200'],
            'subject' => ['sometimes', 'string', 'max:255'],
            'content' => ['sometimes', 'nullable', 'string'],
            'status' => ['sometimes', 'string', Rule::in(['active', 'inactive', 'Active', 'Inactive'])],
        ];
    }
}
