@extends('pdf.layouts.master')

@section('title', 'Fiche Réunion - ' . $meeting->title)

@section('styles')
<style>
    .meeting-header {
        margin-bottom: 20px;
    }
    .meeting-meta {
        display: table;
        width: 100%;
        margin: 15px 0;
    }
    .meeting-meta-row {
        display: table-row;
    }
    .meeting-meta-cell {
        display: table-cell;
        width: 50%;
        padding: 4px 0;
    }
    .delegation-card {
        margin: 15px 0;
        padding: 10px;
        background: #f9fafb;
        border-left: 3px solid #1e3a8a;
        page-break-inside: avoid;
    }
</style>
@endsection

@php
    // Préparation des données pour éviter les erreurs de type
    $statusValue = is_object($meeting->status) && property_exists($meeting->status, 'value')
        ? $meeting->status->value
        : ($meeting->status ?? 'brouillon');
    
    // Récupérer les relations chargées (évite le conflit entre la colonne 'type' et la relation 'type')
    $relations = $meeting->getRelations();
    
    // Type de réunion - utiliser la relation chargée
    $meetingTypeModel = $relations['meetingType'] ?? null;
    $meetingTypeName = is_object($meetingTypeModel) ? $meetingTypeModel->name : null;
    
    // Comité
    $committeeModel = $relations['committee'] ?? null;
    $committeeName = is_object($committeeModel) ? $committeeModel->name : null;
    
    // Organisateur
    $organizerModel = $relations['organizer'] ?? null;
    $organizerName = is_object($organizerModel) ? $organizerModel->name : null;
    
    // Salle
    $roomModel = $relations['room'] ?? null;
    $roomName = is_object($roomModel) ? $roomModel->name : null;
@endphp

@section('content')
{{-- EN-TÊTE DU DOCUMENT --}}
<div class="meeting-header">
    <h1>{{ $meeting->title }}</h1>
    
    <p>
        @include('pdf.partials.status-badge', ['status' => $statusValue, 'type' => 'meeting'])
        @if($meetingTypeName)
            <span class="badge badge-info" style="margin-left: 5px;">{{ $meetingTypeName }}</span>
        @endif
    </p>
</div>

{{-- INFORMATIONS GÉNÉRALES --}}
<div class="section">
    <h2>Informations générales</h2>
    
    @include('pdf.partials.info-table', ['items' => [
        ['label' => 'Type de réunion', 'value' => $meetingTypeName ?? 'Non renseigné'],
        ['label' => 'Comité', 'value' => $committeeName ?? 'Non renseigné'],
        ['label' => 'Organisateur', 'value' => $organizerName ?? 'Non renseigné'],
        ['label' => 'Salle', 'value' => $roomName ?? 'Non renseignée'],
        ['label' => 'Configuration', 'value' => ucfirst($meeting->configuration ?? 'presentiel')],
        ['label' => 'Date de début', 'value' => $meeting->start_at?->format('d/m/Y à H:i') ?? 'Non définie'],
        ['label' => 'Date de fin', 'value' => $meeting->end_at?->format('d/m/Y à H:i') ?? 'Non définie'],
        ['label' => 'Durée', 'value' => $meeting->duration_minutes ? $meeting->duration_minutes . ' minutes' : 'Non définie'],
        ['label' => 'Rappel', 'value' => $meeting->reminder_minutes_before ? $meeting->reminder_minutes_before . ' minutes avant' : 'Aucun rappel'],
    ]])
</div>

{{-- OBJECTIF / DESCRIPTION --}}
@if($meeting->description)
<div class="section">
    <h2>Objectif de la réunion</h2>
    <p class="text-justify">{{ $meeting->description }}</p>
</div>
@endif

{{-- ORDRE DU JOUR --}}
@if($meeting->agenda)
<div class="section">
    <h2>Ordre du jour</h2>
    <div style="white-space: pre-wrap; background: #f9fafb; padding: 12px; border-radius: 4px;">{{ $meeting->agenda }}</div>
</div>
@endif

{{-- COMITÉ D'ORGANISATION --}}
<div class="section avoid-break">
    <h2>Comité d'organisation</h2>
    
    @if($meeting->organizationCommittee)
        <div class="subsection">
            <p><strong>Nom :</strong> {{ $meeting->organizationCommittee->name }}</p>
            @if($meeting->organizationCommittee->description)
                <p class="text-muted">{{ $meeting->organizationCommittee->description }}</p>
            @endif
            @if($meeting->organizationCommittee->host_country)
                <p><strong>Pays hôte :</strong> {{ $meeting->organizationCommittee->host_country }}</p>
            @endif
        </div>
        
        @php $members = $meeting->organizationCommittee->members ?? collect(); @endphp
        @if($members->count())
            <table>
                <thead>
                    <tr>
                        <th>Membre</th>
                        <th>Type</th>
                        <th>Rôle</th>
                        <th>Service/Département</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($members as $member)
                        <tr>
                            <td>{{ $member->user?->name ?? 'Non renseigné' }}</td>
                            <td>
                                @php
                                    $memberType = $member->member_type ?? 'ceeac';
                                    $typeLabels = ['ceeac' => 'CEEAC', 'host_country' => 'Pays hôte'];
                                @endphp
                                {{ $typeLabels[$memberType] ?? $memberType }}
                            </td>
                            <td>{{ $member->role ?? '—' }}</td>
                            <td>{{ $member->department ?? $member->service ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-muted">Aucun membre renseigné dans le comité d'organisation.</p>
        @endif
    @else
        <p class="text-muted">Aucun comité d'organisation associé à cette réunion.</p>
    @endif
</div>

{{-- CAHIER DES CHARGES --}}
@if($meeting->termsOfReference)
<div class="section avoid-break">
    <h2>Cahier des charges</h2>
    
    @include('pdf.partials.info-table', ['items' => [
        ['label' => 'Pays hôte', 'value' => $meeting->termsOfReference->host_country ?? 'Non renseigné'],
        ['label' => 'Date de signature', 'value' => $meeting->termsOfReference->signature_date?->format('d/m/Y') ?? 'Non signé'],
        ['label' => 'Statut', 'value' => ucfirst($meeting->termsOfReference->status ?? 'draft')],
        ['label' => 'Version', 'value' => $meeting->termsOfReference->version ?? '1'],
    ]])
</div>
@endif

{{-- DÉLÉGATIONS PARTICIPANTES --}}
<div class="section">
    <h2>Délégations participantes</h2>
    
    @php $delegations = $meeting->delegations ?? collect(); @endphp
    
    @if($delegations->count())
        <p class="text-muted mb-2">{{ $delegations->count() }} délégation(s) enregistrée(s)</p>
        
        @foreach($delegations as $delegation)
            <div class="delegation-card">
                <h3 style="margin: 0 0 8px 0; color: #1e3a8a;">{{ $delegation->title }}</h3>
                
                @php
                    $entityTypes = [
                        'state_member' => 'État membre',
                        'international_organization' => 'Organisation internationale',
                        'technical_partner' => 'Partenaire technique',
                        'financial_partner' => 'Partenaire financier',
                        'other' => 'Autre'
                    ];
                @endphp
                
                <table style="margin: 5px 0;">
                    <tr>
                        <td style="width: 30%; background: #f3f4f6;"><strong>Type d'entité</strong></td>
                        <td>{{ $entityTypes[$delegation->entity_type] ?? $delegation->entity_type }}</td>
                    </tr>
                    @if($delegation->country)
                    <tr>
                        <td style="background: #f3f4f6;"><strong>Pays</strong></td>
                        <td>{{ $delegation->country }}</td>
                    </tr>
                    @endif
                    @if($delegation->organization_name)
                    <tr>
                        <td style="background: #f3f4f6;"><strong>Organisation</strong></td>
                        <td>{{ $delegation->organization_name }}</td>
                    </tr>
                    @endif
                    @if($delegation->head_of_delegation_name)
                    <tr>
                        <td style="background: #f3f4f6;"><strong>Chef de délégation</strong></td>
                        <td>
                            {{ $delegation->head_of_delegation_name }}
                            @if($delegation->head_of_delegation_position)
                                <span class="text-muted">({{ $delegation->head_of_delegation_position }})</span>
                            @endif
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td style="background: #f3f4f6;"><strong>Statut</strong></td>
                        <td>@include('pdf.partials.status-badge', ['status' => $delegation->participation_status, 'type' => 'delegation'])</td>
                    </tr>
                </table>
                
                {{-- Membres de la délégation --}}
                @php $delegationMembers = $delegation->members ?? collect(); @endphp
                @if($delegationMembers->count())
                    <h4 style="margin: 10px 0 5px 0;">Membres ({{ $delegationMembers->count() }})</h4>
                    <table style="font-size: 9px;">
                        <thead>
                            <tr>
                                <th>Nom complet</th>
                                <th>Email</th>
                                <th>Fonction</th>
                                <th>Rôle</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($delegationMembers as $member)
                                @php
                                    $roles = [
                                        'head' => 'Chef',
                                        'deputy' => 'Adjoint',
                                        'member' => 'Membre',
                                        'advisor' => 'Conseiller',
                                        'expert' => 'Expert',
                                        'interpreter' => 'Interprète'
                                    ];
                                @endphp
                                <tr>
                                    <td>{{ trim(($member->title ?? '') . ' ' . ($member->first_name ?? '') . ' ' . ($member->last_name ?? '')) ?: $member->full_name ?? '—' }}</td>
                                    <td>{{ $member->email ?? '—' }}</td>
                                    <td>{{ $member->position ?? '—' }}</td>
                                    <td>{{ $roles[$member->role] ?? $member->role ?? '—' }}</td>
                                    <td>@include('pdf.partials.status-badge', ['status' => $member->status ?? 'pending', 'type' => 'participant'])</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        @endforeach
    @else
        <p class="text-muted">Aucune délégation enregistrée pour cette réunion.</p>
    @endif
</div>

{{-- DOCUMENTS ASSOCIÉS --}}
<div class="section">
    <h2>Documents associés</h2>
    
    @php $documents = $meeting->documents ?? collect(); @endphp
    
    @if($documents->count())
        <table>
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Type</th>
                    <th>Date d'ajout</th>
                    <th>Auteur</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $doc)
                    <tr>
                        <td>{{ $doc->title }}</td>
                        <td>{{ $doc->type?->name ?? $doc->document_type ?? '—' }}</td>
                        <td>{{ $doc->created_at?->format('d/m/Y') ?? '—' }}</td>
                        <td>{{ $doc->uploader?->name ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-muted">Aucun document associé à cette réunion.</p>
    @endif
</div>
@endsection
