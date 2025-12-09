@extends('pdf.layouts.master')

@section('title', 'Comité d\'organisation - ' . $committee->name)

@section('content')
{{-- EN-TÊTE DU DOCUMENT --}}
<div class="section">
    <h1>Comité d'organisation</h1>
    
    <p>
        @if($committee->is_active)
            <span class="badge badge-success">Actif</span>
        @else
            <span class="badge badge-secondary">Inactif</span>
        @endif
    </p>
</div>

{{-- INFORMATIONS GÉNÉRALES --}}
<div class="section">
    <h2>Informations générales</h2>
    
    @include('pdf.partials.info-table', ['items' => [
        ['label' => 'Nom du comité', 'value' => $committee->name],
        ['label' => 'Description', 'value' => $committee->description],
        ['label' => 'Pays hôte', 'value' => $committee->host_country],
        ['label' => 'Créé par', 'value' => $committee->creator?->name ?? 'Non renseigné'],
        ['label' => 'Date de création', 'value' => $committee->created_at?->format('d/m/Y') ?? 'Non renseignée'],
    ]])
</div>

{{-- RÉUNION ASSOCIÉE --}}
<div class="section">
    <h2>Réunion associée</h2>
    
    @if($committee->meeting)
        <div class="info-box info-box-primary">
            <p><strong>{{ $committee->meeting->title }}</strong></p>
            <p class="text-muted">
                Date : {{ $committee->meeting->start_at?->format('d/m/Y à H:i') ?? 'Non définie' }}
            </p>
            @if($committee->meeting->room)
                <p class="text-small">Salle : {{ $committee->meeting->room->name }}</p>
            @endif
            @if($committee->meeting->meetingType)
                <p class="text-small">Type : {{ $committee->meeting->meetingType->name }}</p>
            @endif
        </div>
    @else
        <p class="text-muted">Aucune réunion associée à ce comité.</p>
    @endif
</div>

{{-- MEMBRES DU COMITÉ --}}
<div class="section">
    <h2>Membres du comité ({{ $committee->members->count() }})</h2>
    
    @if($committee->members->isNotEmpty())
        @php
            $memberTypes = [
                'ceeac' => 'CEEAC',
                'host_country' => 'Pays hôte'
            ];
        @endphp
        
        <table>
            <thead>
                <tr>
                    <th>Membre</th>
                    <th>Type</th>
                    <th>Rôle</th>
                    <th>Service/Département</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($committee->members as $member)
                    <tr>
                        <td>
                            <strong>{{ $member->user?->name ?? 'Non renseigné' }}</strong>
                            @if($member->user?->email)
                                <br><span class="text-muted text-small">{{ $member->user->email }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $member->member_type === 'ceeac' ? 'badge-primary' : 'badge-info' }}">
                                {{ $memberTypes[$member->member_type] ?? $member->member_type ?? 'Non défini' }}
                            </span>
                        </td>
                        <td>{{ $member->role ?? '—' }}</td>
                        <td>
                            @if($member->department || $member->service)
                                {{ $member->department ?? '' }}
                                @if($member->department && $member->service) / @endif
                                {{ $member->service ?? '' }}
                            @else
                                —
                            @endif
                        </td>
                        <td>{{ $member->notes ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        {{-- RÉSUMÉ PAR TYPE --}}
        <div class="info-box info-box-success mt-3">
            <p><strong>Répartition des membres :</strong></p>
            @php
                $ceeacCount = $committee->members->where('member_type', 'ceeac')->count();
                $hostCount = $committee->members->where('member_type', 'host_country')->count();
            @endphp
            <ul style="margin: 5px 0;">
                <li>Membres CEEAC : {{ $ceeacCount }}</li>
                <li>Membres Pays hôte : {{ $hostCount }}</li>
            </ul>
        </div>
    @else
        <p class="text-muted">Aucun membre enregistré dans ce comité d'organisation.</p>
    @endif
</div>
@endsection
