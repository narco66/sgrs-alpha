@extends('pdf.layouts.master')

@section('title', 'Cahier des charges - ' . $meeting->title)

@section('styles')
<style>
    .terms-header {
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 3px solid #1e3a8a;
    }
    .terms-title {
        font-size: 22px;
        color: #1e3a8a;
        margin: 0 0 10px 0;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .terms-subtitle {
        font-size: 14px;
        color: #4b5563;
        margin: 5px 0;
    }
    .terms-version {
        font-size: 11px;
        color: #6b7280;
        margin-top: 10px;
    }
    .content-section {
        margin: 20px 0;
        page-break-inside: avoid;
    }
    .content-title {
        background: #1e3a8a;
        color: #ffffff;
        padding: 10px 15px;
        font-size: 13px;
        font-weight: bold;
        margin-bottom: 10px;
    }
    .content-body {
        padding: 15px;
        background: #f9fafb;
        border-left: 3px solid #1e3a8a;
        text-align: justify;
        white-space: pre-wrap;
    }
</style>
@endsection

@section('content')
{{-- EN-TÊTE SPÉCIAL CAHIER DES CHARGES --}}
<div class="terms-header">
    <div class="terms-title">CAHIER DES CHARGES</div>
    <div class="terms-subtitle">
        Entre la Communauté Économique des États de l'Afrique Centrale (CEEAC)
        <br>et le {{ $termsOfReference->host_country ?? 'Pays hôte' }}
    </div>
    <div class="terms-version">
        Réunion : {{ $meeting->title }}
        <br>Version {{ $termsOfReference->version ?? '1' }}
    </div>
</div>

{{-- INFORMATIONS GÉNÉRALES --}}
<div class="section">
    <h2>Informations générales</h2>
    
    @php
        $statusLabels = [
            'draft' => 'Brouillon',
            'pending_validation' => 'En attente de validation',
            'validated' => 'Validé',
            'signed' => 'Signé',
            'cancelled' => 'Annulé'
        ];
    @endphp
    
    @include('pdf.partials.info-table', ['items' => [
        ['label' => 'Pays hôte', 'value' => $termsOfReference->host_country ?? 'Non renseigné'],
        ['label' => 'Date de signature', 'value' => $termsOfReference->signature_date?->format('d/m/Y') ?? 'Non signé'],
        ['label' => 'Période d\'application', 'value' => 
            ($termsOfReference->effective_from ? $termsOfReference->effective_from->format('d/m/Y') : 'N/A') . 
            ' au ' . 
            ($termsOfReference->effective_until ? $termsOfReference->effective_until->format('d/m/Y') : 'N/A')
        ],
        ['label' => 'Statut', 'value' => $statusLabels[$termsOfReference->status] ?? ucfirst($termsOfReference->status ?? 'draft')],
        ['label' => 'Version', 'value' => $termsOfReference->version ?? '1'],
    ]])
</div>

{{-- RESPONSABILITÉS DE LA CEEAC --}}
<div class="content-section">
    <div class="content-title">1. RESPONSABILITÉS DE LA CEEAC</div>
    <div class="content-body">
        {{ $termsOfReference->responsibilities_ceeac ?? 'Non renseigné' }}
    </div>
</div>

{{-- RESPONSABILITÉS DU PAYS HÔTE --}}
<div class="content-section">
    <div class="content-title">2. RESPONSABILITÉS DU PAYS HÔTE ({{ strtoupper($termsOfReference->host_country ?? 'PAYS HÔTE') }})</div>
    <div class="content-body">
        {{ $termsOfReference->responsibilities_host ?? 'Non renseigné' }}
    </div>
</div>

{{-- PARTAGE DES CHARGES FINANCIÈRES --}}
@if($termsOfReference->financial_sharing)
<div class="content-section">
    <div class="content-title">3. PARTAGE DES CHARGES FINANCIÈRES</div>
    <div class="content-body">
        {{ $termsOfReference->financial_sharing }}
    </div>
</div>
@endif

{{-- PARTAGE DES CHARGES LOGISTIQUES --}}
@if($termsOfReference->logistical_sharing)
<div class="content-section">
    <div class="content-title">4. PARTAGE DES CHARGES LOGISTIQUES</div>
    <div class="content-body">
        {{ $termsOfReference->logistical_sharing }}
    </div>
</div>
@endif

{{-- OBLIGATIONS RESPECTIVES --}}
@if($termsOfReference->obligations_ceeac || $termsOfReference->obligations_host)
<div class="content-section">
    <div class="content-title">5. OBLIGATIONS RESPECTIVES</div>
    
    @if($termsOfReference->obligations_ceeac)
        <h4 style="margin: 15px 0 5px 0;">5.1. Obligations de la CEEAC</h4>
        <div class="content-body">
            {{ $termsOfReference->obligations_ceeac }}
        </div>
    @endif
    
    @if($termsOfReference->obligations_host)
        <h4 style="margin: 15px 0 5px 0;">5.2. Obligations du {{ $termsOfReference->host_country ?? 'Pays hôte' }}</h4>
        <div class="content-body">
            {{ $termsOfReference->obligations_host }}
        </div>
    @endif
</div>
@endif

{{-- TERMES ADDITIONNELS --}}
@if($termsOfReference->additional_terms)
<div class="content-section">
    <div class="content-title">6. TERMES ADDITIONNELS</div>
    <div class="content-body">
        {{ $termsOfReference->additional_terms }}
    </div>
</div>
@endif

{{-- NOTES --}}
@if($termsOfReference->notes)
<div class="content-section">
    <div class="content-title">7. NOTES</div>
    <div class="content-body">
        {{ $termsOfReference->notes }}
    </div>
</div>
@endif

{{-- SIGNATURES --}}
@if($termsOfReference->status === 'signed' || $termsOfReference->isSigned())
    @include('pdf.partials.signature-block', [
        'left' => [
            'title' => 'Pour la CEEAC',
            'name' => $termsOfReference->signerCeeac?->name ?? null,
            'position' => 'Représentant de la Commission de la CEEAC'
        ],
        'right' => [
            'title' => 'Pour le ' . ($termsOfReference->host_country ?? 'Pays hôte'),
            'name' => $termsOfReference->signed_by_host_name ?? null,
            'position' => $termsOfReference->signed_by_host_position ?? null
        ]
    ])
@endif
@endsection
