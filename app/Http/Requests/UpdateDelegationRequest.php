<?php

namespace App\Http\Requests;

use App\Models\Delegation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDelegationRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        if ($user->can('delegations.update')) {
            return true;
        }

        $delegation = $this->route('delegation');
        if ($delegation instanceof Delegation && $delegation->meeting && $user->can('update', $delegation->meeting)) {
            return true;
        }

        return false;
    }

    public function rules(): array
    {
        $delegation = $this->route('delegation');
        $delegationId = $delegation instanceof Delegation ? $delegation->id : null;
        $meetingId = $this->input('meeting_id', $delegation?->meeting_id);

        return [
            'title'                     => [
                'required',
                'string',
                'max:255',
                // Unicité du titre par réunion, en ignorant la délégation courante
                'unique:delegations,title,' . $delegationId . ',id,meeting_id,' . $meetingId,
            ],
            'entity_type'               => ['required', 'string', 'in:' . implode(',', \App\Models\Delegation::entityTypes())],
            'country_code'              => ['nullable', 'string', 'max:3'],
            'country'                   => ['nullable', 'string', 'max:100'],
            'organization_name'         => ['nullable', 'required_if:entity_type,international_organization,technical_partner,financial_partner', 'string', 'max:255'],
            'organization_type'         => ['nullable', 'string', 'max:255'],
            'description'               => ['nullable', 'string'],
            'contact_email'             => ['nullable', 'email', 'max:255'],
            'contact_phone'             => ['nullable', 'string', 'max:50'],
            'head_of_delegation_name'   => ['nullable', 'string', 'max:255'],
            'head_of_delegation_position' => ['nullable', 'string', 'max:255'],
            'head_of_delegation_email'  => ['nullable', 'email', 'max:255'],
            'head_of_delegation_photo'  => ['nullable', 'image', 'max:2048'],
            'meeting_id'                => ['required', 'exists:reunions,id'],
            'participation_status'       => ['nullable', 'string', 'in:' . implode(',', \App\Models\Delegation::participationStatuses())],
            'notes'                     => ['nullable', 'string'],
            'is_active'                 => ['nullable', 'boolean'],
            
            // Membres de la délégation (validation simplifiée - validation manuelle dans le contrôleur)
            'members'                   => ['nullable', 'array'],
            // Utiliser le nom de table standardisé après renommage : delegation_members
            'members.*.id'              => ['nullable', 'exists:delegation_members,id'],
            'members.*.first_name'      => ['nullable', 'string', 'max:255'],
            'members.*.last_name'       => ['nullable', 'string', 'max:255'],
            'members.*.email'           => ['nullable', 'email', 'max:255'],
            'members.*.photo'           => ['nullable', 'image', 'max:2048'],
            'members.*.phone'           => ['nullable', 'string', 'max:50'],
            'members.*.position'        => ['nullable', 'string', 'max:255'],
            'members.*.title'           => ['nullable', 'string', 'max:255'],
            'members.*.institution'     => ['nullable', 'string', 'max:255'],
            'members.*.department'      => ['nullable', 'string', 'max:255'],
            'members.*.role'            => ['nullable', 'string', 'in:head,member,expert,observer,secretary,advisor,interpreter'],
            'members.*.status'          => ['nullable', 'string', 'in:invited,confirmed,present,absent,excused'],
            'members.*.notes'           => ['nullable', 'string'],
        ];
    }
}
