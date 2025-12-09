@extends('pdf.layouts.master')

@section('title', 'Feuille de présence - ' . $meeting->title)

@section('styles')
<style>
    .attendance-header {
        text-align: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #1e3a8a;
    }
    .attendance-title {
        font-size: 18px;
        color: #1e3a8a;
        text-transform: uppercase;
        margin: 10px 0;
    }
    .attendance-meeting {
        font-size: 14px;
        color: #374151;
        margin: 5px 0;
    }
    .attendance-info {
        font-size: 11px;
        color: #6b7280;
    }
    .signature-cell {
        width: 120px;
        height: 40px;
        border: 1px solid #e5e7eb;
    }
    .attendance-table th {
        background: #1e3a8a;
        color: #ffffff;
        font-size: 10px;
        padding: 8px;
    }
    .attendance-table td {
        padding: 6px 8px;
        font-size: 10px;
        vertical-align: middle;
    }
    .empty-row td {
        height: 35px;
    }
</style>
@endsection

@section('content')
{{-- EN-TÊTE --}}
<div class="attendance-header">
    <div class="attendance-title">FEUILLE DE PRÉSENCE</div>
    <div class="attendance-meeting">{{ $meeting->title }}</div>
    <div class="attendance-info">
        Date : {{ $meeting->start_at?->format('d/m/Y') ?? 'Non définie' }} | 
        Heure : {{ $meeting->start_at?->format('H:i') ?? 'Non définie' }} |
        Salle : {{ $meeting->room?->name ?? 'Non définie' }}
    </div>
</div>

@php
    $relations = $meeting->getRelations();
    $meetingTypeName = isset($relations['meetingType']) && is_object($relations['meetingType']) ? $relations['meetingType']->name : null;
    $organizerName = isset($relations['organizer']) && is_object($relations['organizer']) ? $relations['organizer']->name : null;
@endphp

{{-- INFORMATIONS DE LA RÉUNION --}}
<div class="info-box info-box-primary mb-3">
    <table style="width: 100%; border: none;">
        <tr>
            <td style="width: 50%; border: none;">
                <strong>Type de réunion :</strong> {{ $meetingTypeName ?? 'Non défini' }}
            </td>
            <td style="border: none;">
                <strong>Organisateur :</strong> {{ $organizerName ?? 'Non défini' }}
            </td>
        </tr>
    </table>
</div>

{{-- TABLEAU DE PRÉSENCE --}}
<table class="attendance-table">
    <thead>
        <tr>
            <th style="width: 30px;">N°</th>
            <th>Nom et Prénom</th>
            <th style="width: 120px;">Organisation/Pays</th>
            <th style="width: 100px;">Fonction</th>
            <th style="width: 80px;">Téléphone</th>
            <th style="width: 100px;">Email</th>
            <th class="signature-cell">Signature</th>
        </tr>
    </thead>
    <tbody>
        @php $counter = 1; @endphp
        
        {{-- Membres du comité d'organisation --}}
        @if($meeting->organizationCommittee && $meeting->organizationCommittee->members->count())
            @foreach($meeting->organizationCommittee->members as $member)
                <tr>
                    <td style="text-align: center;">{{ $counter++ }}</td>
                    <td>{{ $member->user?->name ?? 'N/A' }}</td>
                    <td>CEEAC (Comité org.)</td>
                    <td>{{ $member->role ?? '—' }}</td>
                    <td>{{ $member->user?->phone ?? '—' }}</td>
                    <td style="font-size: 8px;">{{ $member->user?->email ?? '—' }}</td>
                    <td class="signature-cell"></td>
                </tr>
            @endforeach
        @endif
        
        {{-- Membres des délégations --}}
        @php $delegations = $meeting->delegations ?? collect(); @endphp
        @foreach($delegations as $delegation)
            @php $delegationMembers = $delegation->members ?? collect(); @endphp
            @foreach($delegationMembers as $member)
                <tr>
                    <td style="text-align: center;">{{ $counter++ }}</td>
                    <td>
                        {{ trim(($member->title ?? '') . ' ' . ($member->first_name ?? '') . ' ' . ($member->last_name ?? '')) ?: $member->full_name ?? '—' }}
                    </td>
                    <td>{{ $delegation->title }} ({{ $delegation->country ?? $delegation->organization_name ?? '—' }})</td>
                    <td>{{ $member->position ?? '—' }}</td>
                    <td>{{ $member->phone ?? '—' }}</td>
                    <td style="font-size: 8px;">{{ $member->email ?? '—' }}</td>
                    <td class="signature-cell"></td>
                </tr>
            @endforeach
        @endforeach
        
        {{-- Lignes vides pour participants non enregistrés --}}
        @for($i = 0; $i < 10; $i++)
            <tr class="empty-row">
                <td style="text-align: center;">{{ $counter++ }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="signature-cell"></td>
            </tr>
        @endfor
    </tbody>
</table>

{{-- RÉCAPITULATIF --}}
<div class="section mt-4">
    <table style="width: 50%; margin-left: auto;">
        <tr>
            <td style="background: #f3f4f6; font-weight: bold;">Total présents :</td>
            <td style="width: 100px;"></td>
        </tr>
        <tr>
            <td style="background: #f3f4f6; font-weight: bold;">Total excusés :</td>
            <td></td>
        </tr>
        <tr>
            <td style="background: #f3f4f6; font-weight: bold;">Total absents :</td>
            <td></td>
        </tr>
    </table>
</div>

{{-- VALIDATION --}}
<div class="section mt-4">
    <table style="width: 100%; border: none;">
        <tr>
            <td style="width: 50%; border: none; vertical-align: top;">
                <p><strong>Établi par :</strong></p>
                <p style="margin-top: 40px;">
                    Nom : _________________________
                    <br><br>
                    Date : _________________________
                    <br><br>
                    Signature :
                </p>
            </td>
            <td style="border: none; vertical-align: top;">
                <p><strong>Validé par :</strong></p>
                <p style="margin-top: 40px;">
                    Nom : _________________________
                    <br><br>
                    Fonction : _________________________
                    <br><br>
                    Signature :
                </p>
            </td>
        </tr>
    </table>
</div>
@endsection

