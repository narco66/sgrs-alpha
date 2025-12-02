<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDelegationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('delegations.update');
    }

    public function rules(): array
    {
        $delegation = $this->route('delegation');

        return [
            'title'         => ['required', 'string', 'max:255', Rule::unique('delegations')->ignore($delegation->id)],
            'code'          => ['nullable', 'string', 'max:50', Rule::unique('delegations')->ignore($delegation->id)],
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
