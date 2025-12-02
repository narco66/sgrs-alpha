<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMeetingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'                   => ['required', 'string', 'max:255'],
            'meeting_type_id'         => ['nullable', 'exists:types_reunions,id'],
            'committee_id'            => ['nullable', 'exists:comites,id'],
            'room_id'                 => ['nullable', 'exists:salles,id'],
            'start_at'                => ['required', 'date'],
            'end_at'                  => ['nullable', 'date', 'after_or_equal:start_at'],
            'duration_minutes'        => ['nullable', 'integer', 'min:0'],
            'status'                  => ['nullable', 'string'],
            'description'             => ['nullable', 'string'],
            'agenda'                  => ['nullable', 'string'],
            'reminder_minutes_before' => ['nullable', 'integer', 'min:0'],
            'participants'            => ['nullable', 'array'],
            'participants.*'          => ['exists:utilisateurs,id'],
            'organization_committee_id' => ['nullable', 'exists:comites_organisation,id'], // EF20
        ];
    }
}
