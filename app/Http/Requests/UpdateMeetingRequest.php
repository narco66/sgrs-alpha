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
            'title'                     => ['required', 'string', 'max:255'],
            'meeting_type_id'           => ['required', 'exists:types_reunions,id'],
            'date'                      => ['required', 'date'],
            'time'                      => ['required', 'date_format:H:i'],
            'duration_minutes'          => ['required', 'integer', 'min:15', 'max:1440'],
            'configuration'             => ['required', 'in:presentiel,hybride,visioconference'],
            'room_id'                   => ['nullable', 'exists:salles,id'],
            'description'               => ['nullable', 'string', 'max:5000'],
            'agenda'                    => ['nullable', 'string', 'max:10000'],
            'reminder_minutes_before'   => ['nullable', 'integer', 'min:0', 'max:1440'],
            'committee_id'              => ['nullable', 'exists:comites,id'],
            
            // Comité d'organisation
            'committee_option'          => ['nullable', 'in:existing,new'],
            'organization_committee_id' => [
                'nullable', 
                'required_if:committee_option,existing',
                'exists:comites_organisation,id'
            ],
            'new_committee_name'        => [
                'nullable', 
                'required_if:committee_option,new', 
                'string', 
                'max:255'
            ],
            'new_committee_description' => ['nullable', 'string', 'max:2000'],
            'new_committee_host_country' => ['nullable', 'string', 'max:255'],
            
            // Cahier des charges
            'create_terms_of_reference' => ['nullable', 'boolean'],
            'terms_host_country'        => [
                'nullable', 
                'required_if:create_terms_of_reference,1', 
                'string', 
                'max:255'
            ],
            'terms_signature_date'       => ['nullable', 'date'],
            'terms_responsibilities_ceeac' => ['nullable', 'string'],
            'terms_responsibilities_host' => ['nullable', 'string'],
            'terms_financial_sharing'    => ['nullable', 'string'],
            'terms_logistical_sharing'    => ['nullable', 'string'],
            'terms_signed_document'      => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            
            // Onglet actif pour redirection
            'active_tab'                 => ['nullable', 'string', 'in:general,committee,terms,delegations'],
            
            // Statut
            'status'                    => ['nullable', 'string'],
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
            'committee_id.exists'               => 'Le comité sélectionné est invalide.',
            
            // Messages pour le comité d'organisation
            'organization_committee_id.required_if' => 'Veuillez sélectionner un comité d\'organisation existant.',
            'organization_committee_id.exists'  => 'Le comité d\'organisation sélectionné est invalide.',
            'new_committee_name.required_if'    => 'Le nom du nouveau comité d\'organisation est obligatoire.',
            
            // Messages pour le cahier des charges
            'terms_host_country.required_if'    => 'Le pays hôte est obligatoire pour créer un cahier des charges.',
        ];
    }
}
