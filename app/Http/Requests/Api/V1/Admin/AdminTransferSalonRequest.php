<?php

namespace App\Http\Requests\Api\V1\Admin;

class AdminTransferSalonRequest extends AdminAuthorizedRequest
{
    public function rules(): array
    {
        return [
            'salon_id' => ['required', 'uuid', 'exists:salons,id'],
            'new_owner_id' => ['required', 'uuid', 'exists:users,id'],
        ];
    }
}
