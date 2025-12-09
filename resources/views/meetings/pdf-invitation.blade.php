@extends('pdf.layouts.master')

@section('title', 'Invitation - ' . $meeting->title)

@section('styles')
<style>
    .invitation-header {
        text-align: center;
        margin-bottom: 30px;
    }
    .invitation-title {
        font-size: 18px;
        color: #1e3a8a;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin: 20px 0;
    }
    .invitation-ref {
        font-size: 10px;
        color: #6b7280;
        margin-bottom: 20px;
    }
    .invitation-body {
        text-align: justify;
        line-height: 1.8;
        margin: 20px 0;
    }
    .invitation-details {
        background: #f9fafb;
        padding: 15px;
        border-left: 4px solid #1e3a8a;
        margin: 20px 0;
    }
    .invitation-signature {
        margin-top: 50px;
        text-align: right;
    }
    .invitation-signature-name {
        font-weight: bold;
        margin-top: 40px;
    }
</style>
@endsection

@section('content')
{{-- EN-TÊTE DE L'INVITATION --}}
<div class="invitation-header">
    <div class="invitation-ref">
        Réf: CEEAC/SGRS/{{ date('Y') }}/{{ str_pad($meeting->id, 4, '0', STR_PAD_LEFT) }}
        <br>
        Libreville, le {{ now()->format('d/m/Y') }}
    </div>
    
    <div class="invitation-title">
        LETTRE D'INVITATION
    </div>
</div>

{{-- DESTINATAIRE --}}
<div style="margin-bottom: 30px;">
    @if(isset($recipient))
        <p><strong>À l'attention de :</strong></p>
        <p>{{ $recipient['title'] ?? '' }} {{ $recipient['name'] ?? 'Madame/Monsieur' }}</p>
        @if(isset($recipient['position']))
            <p>{{ $recipient['position'] }}</p>
        @endif
        @if(isset($recipient['organization']))
            <p>{{ $recipient['organization'] }}</p>
        @endif
    @else
        <p><strong>À l'attention de :</strong></p>
        <p>Madame/Monsieur le Représentant</p>
    @endif
</div>

{{-- OBJET --}}
<div style="margin-bottom: 20px;">
    <p><strong>Objet :</strong> Invitation à la réunion « {{ $meeting->title }} »</p>
</div>

{{-- CORPS DE LA LETTRE --}}
<div class="invitation-body">
    <p>Madame, Monsieur,</p>
    
    <p>
        J'ai l'honneur de vous inviter à participer à la réunion « <strong>{{ $meeting->title }}</strong> » 
        organisée par la Commission de la Communauté Économique des États de l'Afrique Centrale (CEEAC).
    </p>
    
    <p>
        Cette réunion se tiendra conformément aux détails suivants :
    </p>
</div>

{{-- DÉTAILS DE LA RÉUNION --}}
<div class="invitation-details">
    <table style="width: 100%; border: none;">
        <tr>
            <td style="width: 35%; font-weight: bold; border: none; padding: 5px 0;">Date :</td>
            <td style="border: none; padding: 5px 0;">{{ $meeting->start_at?->format('d/m/Y') ?? 'À confirmer' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; border: none; padding: 5px 0;">Heure :</td>
            <td style="border: none; padding: 5px 0;">{{ $meeting->start_at?->format('H:i') ?? 'À confirmer' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; border: none; padding: 5px 0;">Lieu :</td>
            <td style="border: none; padding: 5px 0;">
                @if($meeting->room)
                    {{ $meeting->room->name }}
                    @if($meeting->room->address)
                        <br>{{ $meeting->room->address }}
                    @endif
                @else
                    Siège de la CEEAC, Libreville, Gabon
                @endif
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold; border: none; padding: 5px 0;">Configuration :</td>
            <td style="border: none; padding: 5px 0;">
                @php
                    $configs = [
                        'presentiel' => 'En présentiel',
                        'hybride' => 'Hybride (présentiel et visioconférence)',
                        'visioconference' => 'Visioconférence'
                    ];
                @endphp
                {{ $configs[$meeting->configuration ?? 'presentiel'] ?? 'En présentiel' }}
            </td>
        </tr>
        @php
            $relations = $meeting->getRelations();
            $meetingTypeName = isset($relations['meetingType']) && is_object($relations['meetingType']) ? $relations['meetingType']->name : null;
        @endphp
        @if($meetingTypeName)
        <tr>
            <td style="font-weight: bold; border: none; padding: 5px 0;">Type de réunion :</td>
            <td style="border: none; padding: 5px 0;">{{ $meetingTypeName }}</td>
        </tr>
        @endif
    </table>
</div>

{{-- ORDRE DU JOUR --}}
@if($meeting->agenda)
<div class="section">
    <h3>Ordre du jour provisoire</h3>
    <div style="background: #f9fafb; padding: 10px; white-space: pre-wrap;">{{ $meeting->agenda }}</div>
</div>
@endif

{{-- INSTRUCTIONS --}}
<div class="invitation-body">
    <p>
        Nous vous prions de bien vouloir confirmer votre participation en retournant le formulaire 
        de confirmation ci-joint ou en contactant le secrétariat de la réunion avant le 
        <strong>{{ $meeting->start_at?->subDays(7)->format('d/m/Y') ?? 'date à confirmer' }}</strong>.
    </p>
    
    <p>
        Pour toute information complémentaire, veuillez contacter :
        <br>Email : commission@ceeac-eccas.org
        <br>Tél : +(241) 44 47 31 / 44 47 34
    </p>
    
    <p>
        Dans l'attente de votre participation, nous vous prions d'agréer, Madame, Monsieur, 
        l'expression de notre haute considération.
    </p>
</div>

{{-- SIGNATURE --}}
<div class="invitation-signature">
    <p>Le Président de la Commission</p>
    <div class="invitation-signature-name">
        ____________________________
        <br><br>
        <em>Commission de la CEEAC</em>
    </div>
</div>

{{-- PIÈCES JOINTES --}}
<div style="margin-top: 40px; font-size: 10px; color: #6b7280;">
    <p><strong>Pièces jointes :</strong></p>
    <ul>
        <li>Formulaire de confirmation de participation</li>
        <li>Note d'information logistique</li>
        @if($meeting->agenda)
            <li>Ordre du jour détaillé</li>
        @endif
    </ul>
</div>
@endsection

