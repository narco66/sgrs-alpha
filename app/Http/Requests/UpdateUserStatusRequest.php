<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        if (! $user) {
            return false;
        }

        return $user->hasAnyRole(['super-admin', 'admin', 'dsi'])
            || $user->can('users.manage');
    }

    public function rules(): array
    {
        return [
            'status'    => ['required', 'string', 'in:active,inactive,pending,rejected'],
            'roles'     => ['nullable', 'array'],
            'roles.*'   => ['integer', 'exists:roles,id'],
        ];
    }
}


