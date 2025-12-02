<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMeetingRequest extends FormRequest
{
    public function authorize(): bool
    {
        // L'autorisation fine est gérée par la policy, on autorise ici.
        return true;
    }

    public function rules(): array
    {
        return [
            'title'                     => ['required', 'string', 'max:255'],
            'meeting_type_id'           => ['required', 'exists:types_reunions,id'],
            'date'                      => ['required', 'date'],
            'time'                      => ['required', 'date_format:H:i'],
            'duration_minutes'          => ['required', 'integer', 'min:15', 'max:1440'],
            'configuration'             => ['required', 'in:presentiel,hybride,visioconference'],
            'room_id'                   => ['nullable', 'exists:salles,id'],
            'description'               => ['nullable', 'string', 'max:5000'],
            'reminder_minutes_before'   => ['nullable', 'integer', 'min:0', 'max:1440'],
            'participants'              => ['nullable', 'array'],
            // Le formulaire envoie des IDs d'utilisateurs : vérifier dans la table utilisateurs
            'participants.*'            => ['integer', 'exists:utilisateurs,id'],
            'committee_id'              => ['nullable', 'exists:comites,id'],
            'organization_committee_id' => ['nullable', 'exists:comites_organisation,id'], // EF20
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'                    => 'Le titre de la réunion est obligatoire.',
            'meeting_type_id.required'          => 'Le type de réunion est obligatoire.',
            'meeting_type_id.exists'            => 'Le type de réunion sélectionné est invalide.',
            'date.required'                     => 'La date de la réunion est obligatoire.',
            'time.required'                     => "L'heure de la réunion est obligatoire.",
            'room_id.exists'                    => 'La salle sélectionnée est invalide.',
            'participants.*.exists'             => 'Un participant sélectionné n’existe pas.',
            'committee_id.exists'               => 'Le comité sélectionné est invalide.',
            'organization_committee_id.exists'  => 'Le comité d’organisation sélectionné est invalide.',
        ];
    }
}
