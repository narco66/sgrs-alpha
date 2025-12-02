<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommitteeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'            => ['required', 'string', 'max:255'],
            'code'            => ['required', 'string', 'max:20', 'unique:comites,code'],
            'meeting_type_id' => ['nullable', 'exists:types_reunions,id'],
            'is_permanent'    => ['nullable', 'boolean'],
            'is_active'       => ['nullable', 'boolean'],
            'description'     => ['nullable', 'string'],
            'sort_order'      => ['nullable', 'integer', 'min:0'],
        ];
    }
}
