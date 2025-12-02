<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDelegationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('delegations.create');
    }

    public function rules(): array
    {
        return [
            'title'         => ['required', 'string', 'max:255', 'unique:delegations,title'],
            'code'          => ['nullable', 'string', 'max:50', 'unique:delegations,code'],
            'country'       => ['nullable', 'string', 'max:100'],
            'description'   => ['nullable', 'string'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'address'       => ['nullable', 'string', 'max:500'],
            'meeting_id'    => ['required', 'exists:reunions,id'],
            'participants'  => ['nullable', 'array'],
            'participants.*'=> ['integer', 'exists:utilisateurs,id'],
            'is_active'     => ['nullable', 'boolean'],
        ];
    }
}
