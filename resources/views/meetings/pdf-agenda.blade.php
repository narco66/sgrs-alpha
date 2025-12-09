@extends('pdf.layouts.master')

@section('title', 'Ordre du jour - ' . $meeting->title)

@section('styles')
<style>
    .agenda-header {
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 3px solid #1e3a8a;
    }
    .agenda-title {
        font-size: 20px;
        color: #1e3a8a;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin: 10px 0;
    }
    .agenda-item {
        margin: 15px 0;
        padding: 12px;
        background: #f9fafb;
        border-left: 4px solid #1e3a8a;
        page-break-inside: avoid;
    }
    .agenda-item-number {
        font-weight: bold;
        color: #1e3a8a;
        font-size: 14px;
    }
    .agenda-item-title {
        font-weight: bold;
        font-size: 12px;
        margin: 5px 0;
    }
    .agenda-item-time {
        color: #6b7280;
        font-size: 10px;
    }
    .agenda-item-description {
        margin-top: 8px;
        font-size: 11px;
        color: #4b5563;
    }
</style>
@endsection

@section('content')
{{-- EN-TÊTE --}}
<div class="agenda-header">
    <div class="agenda-title">ORDRE DU JOUR</div>
    <p style="font-size: 14px; color: #374151; margin: 10px 0;">{{ $meeting->title }}</p>
    <p class="text-muted">
        {{ $meeting->start_at?->format('l d F Y') ?? 'Date à confirmer' }}
        @if($meeting->room)
            | {{ $meeting->room->name }}
        @endif
    </p>
</div>

@php
    $relations = $meeting->getRelations();
    $meetingTypeName = isset($relations['meetingType']) && is_object($relations['meetingType']) ? $relations['meetingType']->name : null;
@endphp

{{-- INFORMATIONS DE LA RÉUNION --}}
<div class="info-box info-box-primary mb-3">
    <table style="width: 100%; border: none;">
        <tr>
            <td style="width: 25%; border: none;"><strong>Type :</strong> {{ $meetingTypeName ?? '—' }}</td>
            <td style="width: 25%; border: none;"><strong>Début :</strong> {{ $meeting->start_at?->format('H:i') ?? '—' }}</td>
            <td style="width: 25%; border: none;"><strong>Fin prévue :</strong> {{ $meeting->end_at?->format('H:i') ?? '—' }}</td>
            <td style="border: none;"><strong>Durée :</strong> {{ $meeting->duration_minutes ?? '—' }} min</td>
        </tr>
    </table>
</div>

{{-- ORDRE DU JOUR DÉTAILLÉ --}}
<div class="section">
    <h2>Programme détaillé</h2>
    
    {{-- Accueil --}}
    <div class="agenda-item">
        <span class="agenda-item-time">{{ $meeting->start_at?->subMinutes(30)->format('H:i') ?? '—' }} - {{ $meeting->start_at?->format('H:i') ?? '—' }}</span>
        <div class="agenda-item-title">Accueil et inscription des participants</div>
        <div class="agenda-item-description">
            Accréditation et remise des documents de travail
        </div>
    </div>
    
    {{-- Ouverture --}}
    <div class="agenda-item">
        <span class="agenda-item-number">Point 1</span>
        <span class="agenda-item-time" style="margin-left: 10px;">{{ $meeting->start_at?->format('H:i') ?? '—' }}</span>
        <div class="agenda-item-title">Ouverture de la réunion</div>
        <div class="agenda-item-description">
            - Mot de bienvenue
            <br>- Présentation des participants
            <br>- Adoption de l'ordre du jour
        </div>
    </div>
    
    {{-- Contenu de l'agenda si disponible --}}
    @if($meeting->agenda)
        @php
            $agendaLines = array_filter(explode("\n", $meeting->agenda));
            $pointNumber = 2;
        @endphp
        
        @foreach($agendaLines as $line)
            @if(trim($line))
                <div class="agenda-item">
                    <span class="agenda-item-number">Point {{ $pointNumber++ }}</span>
                    <div class="agenda-item-title">{{ trim($line) }}</div>
                </div>
            @endif
        @endforeach
    @else
        {{-- Points génériques si pas d'agenda défini --}}
        <div class="agenda-item">
            <span class="agenda-item-number">Point 2</span>
            <div class="agenda-item-title">Examen du rapport / Présentation principale</div>
            <div class="agenda-item-description">
                <em>[À compléter]</em>
            </div>
        </div>
        
        <div class="agenda-item">
            <span class="agenda-item-number">Point 3</span>
            <div class="agenda-item-title">Discussions et échanges</div>
            <div class="agenda-item-description">
                <em>[À compléter]</em>
            </div>
        </div>
        
        <div class="agenda-item">
            <span class="agenda-item-number">Point 4</span>
            <div class="agenda-item-title">Décisions et recommandations</div>
            <div class="agenda-item-description">
                <em>[À compléter]</em>
            </div>
        </div>
    @endif
    
    {{-- Pause --}}
    <div class="agenda-item" style="background: #fef3c7; border-left-color: #f59e0b;">
        <span class="agenda-item-time">Pause</span>
        <div class="agenda-item-title">Pause café / Pause déjeuner</div>
        <div class="agenda-item-description">
            Rafraîchissements servis dans le hall
        </div>
    </div>
    
    {{-- Divers --}}
    <div class="agenda-item">
        <span class="agenda-item-number">Divers</span>
        <div class="agenda-item-title">Questions diverses</div>
        <div class="agenda-item-description">
            Points soulevés par les participants
        </div>
    </div>
    
    {{-- Clôture --}}
    <div class="agenda-item">
        <span class="agenda-item-time">{{ $meeting->end_at?->format('H:i') ?? '—' }}</span>
        <div class="agenda-item-title">Clôture de la réunion</div>
        <div class="agenda-item-description">
            - Synthèse des travaux
            <br>- Prochaines étapes
            <br>- Mot de clôture
        </div>
    </div>
</div>

{{-- DOCUMENTS DE RÉFÉRENCE --}}
@php $documents = $meeting->documents ?? collect(); @endphp
@if($documents->count())
<div class="section">
    <h2>Documents de référence</h2>
    <table>
        <thead>
            <tr>
                <th>Référence</th>
                <th>Titre du document</th>
                <th>Type</th>
            </tr>
        </thead>
        <tbody>
            @foreach($documents as $index => $doc)
                <tr>
                    <td>DOC-{{ $index + 1 }}</td>
                    <td>{{ $doc->title }}</td>
                    <td>{{ $doc->type?->name ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

{{-- NOTE --}}
<div class="section mt-3">
    <div class="info-box info-box-warning">
        <p class="text-small">
            <strong>Note :</strong> Cet ordre du jour est provisoire et pourrait être modifié. 
            Toute modification sera communiquée aux participants en temps utile.
        </p>
    </div>
</div>
@endsection

