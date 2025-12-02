<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParticipantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Policy gère le détail
    }

    public function rules(): array
    {
        return [
            'last_name'   => ['required', 'string', 'max:100'],
            'first_name'  => ['required', 'string', 'max:100'],
            'email'       => ['nullable', 'email', 'max:190'],
            'phone'       => ['nullable', 'string', 'max:50'],
            'position'    => ['nullable', 'string', 'max:150'],
            'institution' => ['nullable', 'string', 'max:190'],
            'country'     => ['nullable', 'string', 'max:100'],
            'is_internal' => ['required', 'boolean'],
            'is_active'   => ['required', 'boolean'],
        ];
    }
}
