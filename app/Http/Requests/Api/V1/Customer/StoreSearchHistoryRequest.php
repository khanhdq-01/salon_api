<?php

namespace App\Http\Requests\Api\V1\Customer;

use App\Models\SearchHistory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSearchHistoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in([SearchHistory::TYPE_QUERY, SearchHistory::TYPE_SALON])],
            'query' => ['required_if:type,query', 'nullable', 'string', 'min:1', 'max:255'],
            'salon_id' => ['required_if:type,salon', 'nullable', 'uuid', 'exists:salons,id'],
        ];
    }
}
