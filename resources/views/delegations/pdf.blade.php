@extends('pdf.layouts.master')

@section('title', 'Délégation - ' . $delegation->title)

@section('content')
{{-- EN-TÊTE DU DOCUMENT --}}
<div class="section">
    <h1>{{ $delegation->title }}</h1>
    
    <p>
        @if($delegation->is_active)
            <span class="badge badge-success">Actif</span>
        @else
            <span class="badge badge-secondary">Inactif</span>
        @endif
        
        @if($delegation->participation_status)
            @include('pdf.partials.status-badge', ['status' => $delegation->participation_status, 'type' => 'delegation'])
        @endif
    </p>
    
    @if($delegation->code || $delegation->country)
        <p class="text-muted">
            @if($delegation->code) Code : {{ $delegation->code }} @endif
            @if($delegation->code && $delegation->country) — @endif
            @if($delegation->country) Pays : {{ $delegation->country }} @endif
        </p>
    @endif
</div>

{{-- INFORMATIONS GÉNÉRALES --}}
<div class="section">
    <h2>Informations générales</h2>
    
    @php
        $entityTypes = [
            'state_member' => 'État membre',
            'international_organization' => 'Organisation internationale',
            'technical_partner' => 'Partenaire technique',
            'financial_partner' => 'Partenaire financier',
            'other' => 'Autre'
        ];
    @endphp
    
    @include('pdf.partials.info-table', ['items' => [
        ['label' => 'Type d\'entité', 'value' => $entityTypes[$delegation->entity_type] ?? $delegation->entity_type ?? 'Non renseigné'],
        ['label' => 'Pays', 'value' => $delegation->country],
        ['label' => 'Organisation', 'value' => $delegation->organization_name],
        ['label' => 'Email de contact', 'value' => $delegation->contact_email],
        ['label' => 'Téléphone', 'value' => $delegation->contact_phone],
        ['label' => 'Adresse', 'value' => $delegation->address],
    ]])
</div>

{{-- CHEF DE DÉLÉGATION --}}
@if($delegation->head_of_delegation_name)
<div class="section">
    <h2>Chef de délégation</h2>
    
    @include('pdf.partials.info-table', ['items' => [
        ['label' => 'Nom', 'value' => $delegation->head_of_delegation_name],
        ['label' => 'Fonction', 'value' => $delegation->head_of_delegation_position],
        ['label' => 'Email', 'value' => $delegation->head_of_delegation_email],
    ]])
</div>
@endif

{{-- PRÉSENTATION --}}
@if($delegation->description)
<div class="section">
    <h2>Présentation</h2>
    <p class="text-justify">{{ $delegation->description }}</p>
</div>
@endif

{{-- RÉUNION ASSOCIÉE --}}
<div class="section">
    <h2>Réunion associée</h2>
    
    @if($delegation->meeting)
        <div class="info-box info-box-primary">
            <p><strong>{{ $delegation->meeting->title }}</strong></p>
            <p class="text-muted">
                {{ $delegation->meeting->start_at?->format('d/m/Y à H:i') ?? 'Date non définie' }}
                @if($delegation->meeting->room)
                    — Salle : {{ $delegation->meeting->room->name }}
                @endif
            </p>
            @if($delegation->meeting->type)
                <p class="text-small">Type : {{ $delegation->meeting->type->name }}</p>
            @endif
        </div>
    @else
        <p class="text-muted">Aucune réunion associée à cette délégation.</p>
    @endif
</div>

{{-- MEMBRES DE LA DÉLÉGATION --}}
<div class="section">
    <h2>Membres de la délégation</h2>
    
    @php $members = $delegation->members ?? collect(); @endphp
    
    @if($members->count())
        <p class="text-muted mb-2">{{ $members->count() }} membre(s) enregistré(s)</p>
        
        @php
            $roles = [
                'head' => 'Chef de délégation',
                'deputy' => 'Chef adjoint',
                'member' => 'Membre',
                'advisor' => 'Conseiller',
                'expert' => 'Expert',
                'interpreter' => 'Interprète'
            ];
        @endphp
        
        <table>
            <thead>
                <tr>
                    <th>Nom complet</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Fonction</th>
                    <th>Rôle</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach($members as $member)
                    <tr>
                        <td>
                            {{ trim(($member->title ?? '') . ' ' . ($member->first_name ?? '') . ' ' . ($member->last_name ?? '')) ?: $member->full_name ?? '—' }}
                        </td>
                        <td>{{ $member->email ?? '—' }}</td>
                        <td>{{ $member->phone ?? '—' }}</td>
                        <td>{{ $member->position ?? '—' }}</td>
                        <td>{{ $roles[$member->role] ?? $member->role ?? '—' }}</td>
                        <td>@include('pdf.partials.status-badge', ['status' => $member->status ?? 'pending', 'type' => 'participant'])</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-muted">Aucun membre enregistré pour cette délégation.</p>
    @endif
</div>

{{-- PARTICIPANTS (si relation différente de members) --}}
@if(isset($delegation->participants) && $delegation->participants->count())
<div class="section">
    <h2>Participants utilisateurs</h2>
    
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Service</th>
                <th>Rôle</th>
            </tr>
        </thead>
        <tbody>
            @foreach($delegation->participants as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email ?? '—' }}</td>
                    <td>{{ $user->service ?? '—' }}</td>
                    <td>{{ $user->pivot->role ?? 'Participant' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

{{-- NOTES --}}
@if($delegation->notes)
<div class="section">
    <h2>Notes</h2>
    <div class="info-box info-box-warning">
        <p>{{ $delegation->notes }}</p>
    </div>
</div>
@endif
@endsection
