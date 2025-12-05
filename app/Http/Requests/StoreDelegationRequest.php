<?php

namespace App\Http\Requests;

use App\Models\Meeting;
use Illuminate\Foundation\Http\FormRequest;

class StoreDelegationRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        if ($user->can('delegations.create')) {
            return true;
        }

        $meetingId = $this->input('meeting_id');
        if ($meetingId) {
            $meeting = Meeting::find($meetingId);
            if ($meeting && $user->can('update', $meeting)) {
                return true;
            }
        }

        return false;
    }

    public function rules(): array
    {
        $entityType = $this->input('entity_type');
        
        $rules = [
            'title'                     => ['required', 'string', 'max:255'],
            'entity_type'               => ['required', 'string', 'in:' . implode(',', \App\Models\Delegation::entityTypes())],
            'country_code'              => ['nullable', 'string', 'max:3'],
            'country'                   => ['nullable', 'string', 'max:100'],
            'organization_name'         => ['nullable', 'string', 'max:255'],
            'organization_type'         => ['nullable', 'string', 'max:255'],
            'description'               => ['nullable', 'string'],
            'contact_email'             => ['nullable', 'email', 'max:255'],
            'contact_phone'             => ['nullable', 'string', 'max:50'],
            'head_of_delegation_name'   => ['nullable', 'string', 'max:255'],
            'head_of_delegation_position' => ['nullable', 'string', 'max:255'],
            'head_of_delegation_email'  => ['nullable', 'email', 'max:255'],
            'meeting_id'                => ['required', 'exists:reunions,id'],
            'participation_status'       => ['nullable', 'string', 'in:' . implode(',', \App\Models\Delegation::participationStatuses())],
            'notes'                     => ['nullable', 'string'],
            'is_active'                 => ['nullable', 'boolean'],
            
            // Membres de la délégation (validation simplifiée - validation manuelle dans le contrôleur)
            'members'                   => ['nullable', 'array'],
            'members.*.id'              => ['nullable', 'exists:membres_delegations,id'],
            'members.*.first_name'      => ['nullable', 'string', 'max:255'],
            'members.*.last_name'       => ['nullable', 'string', 'max:255'],
            'members.*.email'           => ['nullable', 'email', 'max:255'],
            'members.*.phone'           => ['nullable', 'string', 'max:50'],
            'members.*.position'        => ['nullable', 'string', 'max:255'],
            'members.*.title'           => ['nullable', 'string', 'max:255'],
            'members.*.institution'     => ['nullable', 'string', 'max:255'],
            'members.*.department'      => ['nullable', 'string', 'max:255'],
            'members.*.role'            => ['nullable', 'string', 'in:head,member,expert,observer,secretary'],
            'members.*.status'          => ['nullable', 'string', 'in:invited,confirmed,present,absent,excused'],
            'members.*.notes'           => ['nullable', 'string'],
        ];
        
        // Validation conditionnelle
        if (in_array($entityType, ['state_member', 'other'])) {
            $rules['country'][] = 'required';
        }
        
        if (in_array($entityType, ['international_organization', 'technical_partner', 'financial_partner'])) {
            $rules['organization_name'][] = 'required';
        }
        
        return $rules;
    }
    
    public function messages(): array
    {
        return [
            'title.required' => 'Le titre de la délégation est requis.',
            'entity_type.required' => 'Le type d\'entité est requis.',
            'country.required' => 'Le pays est requis pour ce type d\'entité.',
            'organization_name.required' => 'Le nom de l\'organisation est requis pour ce type d\'entité.',
            'meeting_id.required' => 'La réunion associée est requise.',
            'meeting_id.exists' => 'La réunion sélectionnée n\'existe pas.',
        ];
    }
}
