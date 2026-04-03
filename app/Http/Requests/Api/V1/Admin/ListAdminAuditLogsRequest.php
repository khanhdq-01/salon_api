<?php

namespace App\Http\Requests\Api\V1\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListAdminAuditLogsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:200'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'user_id' => ['nullable', 'uuid'],
            'role' => ['nullable', 'string', Rule::in(['admin', 'owner', 'staff', 'customer', 'system'])],
            'module' => ['nullable', 'string', Rule::in([
                'booking', 'salon', 'service', 'staff', 'customer',
                'subscription', 'payment', 'user', 'notification', 'settings',
            ])],
            'action' => ['nullable', 'string', Rule::in([
                'create', 'update', 'delete', 'view', 'list',
                'login', 'logout', 'register',
                'cancel', 'reschedule', 'approve', 'reject', 'lock', 'unlock',
                'activate', 'deactivate', 'confirm', 'complete',
                'upload', 'broadcast', 'transfer', 'resolve',
            ])],
            'salon_id' => ['nullable', 'uuid'],
            'target_type' => ['nullable', 'string', 'max:50'],
            'status' => ['nullable', 'string', 'max:20'],
            'page' => ['nullable', 'integer', 'min:1'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
