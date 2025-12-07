@extends('pdf.layouts.master')

@section('title', 'Note logistique - ' . $meeting->title)

@section('styles')
<style>
    .logistics-header {
        text-align: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #1e3a8a;
    }
    .logistics-title {
        font-size: 18px;
        color: #1e3a8a;
        text-transform: uppercase;
        margin: 10px 0;
    }
    .info-card {
        background: #f9fafb;
        padding: 15px;
        margin: 15px 0;
        border-left: 4px solid #1e3a8a;
        page-break-inside: avoid;
    }
    .info-card-title {
        font-weight: bold;
        color: #1e3a8a;
        margin-bottom: 10px;
        font-size: 13px;
    }
    .contact-box {
        background: #eff6ff;
        padding: 12px;
        border-radius: 4px;
        margin: 10px 0;
    }
</style>
@endsection

@section('content')
{{-- EN-TÊTE --}}
<div class="logistics-header">
    <div class="logistics-title">NOTE D'INFORMATION LOGISTIQUE</div>
    <p class="text-muted">{{ $meeting->title }}</p>
    <p class="text-small text-muted">
        Réf: CEEAC/LOG/{{ date('Y') }}/{{ str_pad($meeting->id, 4, '0', STR_PAD_LEFT) }}
    </p>
</div>

{{-- INTRODUCTION --}}
<div class="section">
    <p class="text-justify">
        La présente note a pour objet de fournir aux participants les informations pratiques 
        relatives à l'organisation de la réunion « <strong>{{ $meeting->title }}</strong> ».
    </p>
</div>

{{-- DATE ET LIEU --}}
<div class="info-card">
    <div class="info-card-title">1. DATE ET LIEU</div>
    <table style="width: 100%; border: none;">
        <tr>
            <td style="width: 30%; font-weight: bold; border: none; padding: 4px 0;">Date :</td>
            <td style="border: none;">{{ $meeting->start_at?->format('l d F Y') ?? 'À confirmer' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; border: none; padding: 4px 0;">Horaires :</td>
            <td style="border: none;">
                {{ $meeting->start_at?->format('H:i') ?? '—' }} - {{ $meeting->end_at?->format('H:i') ?? '—' }}
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold; border: none; padding: 4px 0;">Lieu :</td>
            <td style="border: none;">
                @if($meeting->room)
                    {{ $meeting->room->name }}
                    @if($meeting->room->building)
                        <br>Bâtiment : {{ $meeting->room->building }}
                    @endif
                    @if($meeting->room->address)
                        <br>{{ $meeting->room->address }}
                    @endif
                @else
                    Siège de la Commission de la CEEAC
                    <br>BP 2112, Libreville, GABON
                @endif
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold; border: none; padding: 4px 0;">Configuration :</td>
            <td style="border: none;">
                @php
                    $configs = [
                        'presentiel' => 'Réunion en présentiel',
                        'hybride' => 'Réunion hybride (présentiel et visioconférence)',
                        'visioconference' => 'Réunion en visioconférence uniquement'
                    ];
                @endphp
                {{ $configs[$meeting->configuration ?? 'presentiel'] ?? 'En présentiel' }}
            </td>
        </tr>
    </table>
</div>

{{-- INSCRIPTION ET ACCRÉDITATION --}}
<div class="info-card">
    <div class="info-card-title">2. INSCRIPTION ET ACCRÉDITATION</div>
    <p>
        Les délégations sont priées de communiquer la liste de leurs participants au plus tard 
        <strong>{{ $meeting->start_at?->subDays(7)->format('d/m/Y') ?? '7 jours avant la réunion' }}</strong>.
    </p>
    <p>
        L'accréditation des participants se fera sur place le jour de la réunion, 
        30 minutes avant le début de la séance. Chaque participant devra présenter une pièce d'identité valide.
    </p>
</div>

{{-- DOCUMENTS DE TRAVAIL --}}
<div class="info-card">
    <div class="info-card-title">3. DOCUMENTS DE TRAVAIL</div>
    <p>
        Les documents de travail seront mis à la disposition des participants :
    </p>
    <ul>
        <li>En version électronique via la plateforme SGRS-CEEAC</li>
        <li>En version papier le jour de la réunion (nombre limité)</li>
    </ul>
    
    @php $documents = $meeting->documents ?? collect(); @endphp
    @if($documents->count())
        <p style="margin-top: 10px;"><strong>Documents disponibles :</strong></p>
        <table>
            <thead>
                <tr>
                    <th>Document</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $doc)
                    <tr>
                        <td>{{ $doc->title }}</td>
                        <td>{{ $doc->type?->name ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

{{-- LANGUES DE TRAVAIL --}}
<div class="info-card">
    <div class="info-card-title">4. LANGUES DE TRAVAIL</div>
    <p>
        Les langues de travail de la réunion sont le <strong>français</strong> et le <strong>portugais</strong>.
        Une interprétation simultanée sera assurée si nécessaire.
    </p>
</div>

{{-- HÉBERGEMENT (si applicable) --}}
@if($meeting->termsOfReference || ($meeting->configuration ?? 'presentiel') === 'presentiel')
<div class="info-card">
    <div class="info-card-title">5. HÉBERGEMENT</div>
    <p>
        @if($meeting->termsOfReference && $meeting->termsOfReference->host_country)
            Conformément au cahier des charges établi avec le {{ $meeting->termsOfReference->host_country }},
            les modalités d'hébergement sont les suivantes :
        @else
            Les participants sont invités à prendre leurs propres dispositions en matière d'hébergement.
            Voici quelques hôtels recommandés à Libreville :
        @endif
    </p>
    <ul>
        <li>Radisson Blu Okoumé Palace - Tél: +241 XX XX XX XX</li>
        <li>Hôtel Nomad - Tél: +241 XX XX XX XX</li>
        <li>Park Inn by Radisson - Tél: +241 XX XX XX XX</li>
    </ul>
    <p class="text-small text-muted">
        <em>Note : Un tarif préférentiel peut être négocié. Contactez le secrétariat pour plus d'informations.</em>
    </p>
</div>
@endif

{{-- TRANSPORT --}}
<div class="info-card">
    <div class="info-card-title">6. TRANSPORT</div>
    <p>
        <strong>Aéroport international Léon Mba (LBV)</strong> - Libreville, Gabon
    </p>
    <p>
        Les participants sont priés de communiquer leurs informations de vol pour permettre 
        l'organisation de leur accueil à l'aéroport.
    </p>
    <p>
        Un service de navette pourra être organisé entre les hôtels et le lieu de la réunion.
    </p>
</div>

{{-- CONTACTS --}}
<div class="info-card">
    <div class="info-card-title">7. CONTACTS</div>
    
    <div class="contact-box">
        <p><strong>Secrétariat de la réunion</strong></p>
        <p>Commission de la CEEAC</p>
        <p>Email : commission@ceeac-eccas.org</p>
        <p>Tél : +(241) 44 47 31 / 44 47 34</p>
    </div>
    
    @if($meeting->organizer)
    <div class="contact-box">
        <p><strong>Organisateur principal</strong></p>
        <p>{{ $meeting->organizer->name }}</p>
        @if($meeting->organizer->email)
            <p>Email : {{ $meeting->organizer->email }}</p>
        @endif
        @if($meeting->organizer->phone)
            <p>Tél : {{ $meeting->organizer->phone }}</p>
        @endif
    </div>
    @endif
</div>

{{-- URGENCES --}}
<div class="info-card">
    <div class="info-card-title">8. NUMÉROS D'URGENCE</div>
    <table style="width: 100%; border: none;">
        <tr>
            <td style="width: 50%; border: none; padding: 4px 0;">Police : 177</td>
            <td style="border: none; padding: 4px 0;">Pompiers : 18</td>
        </tr>
        <tr>
            <td style="border: none; padding: 4px 0;">SAMU : 1300</td>
            <td style="border: none; padding: 4px 0;">Urgences médicales : +241 XX XX XX XX</td>
        </tr>
    </table>
</div>

{{-- NOTE FINALE --}}
<div class="section mt-3">
    <div class="info-box info-box-warning">
        <p class="text-small">
            <strong>Important :</strong> Pour toute modification ou annulation de participation, 
            veuillez en informer le secrétariat au moins 48 heures à l'avance.
        </p>
    </div>
</div>
@endsection

