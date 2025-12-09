<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Badge - {{ $participant->full_name ?? $participant->first_name ?? 'Participant' }}</title>
    <style>
        /**
         * Template de badge individuel pour SGRS-CEEAC
         * Format: 85mm x 54mm (format carte de crédit)
         * Encodage: UTF-8 pour les accents français
         */
        
        /* Configuration de la page pour DomPDF */
        @page {
            margin: 0;
            padding: 0;
            size: 85mm 54mm landscape;
        }
        
        /* Reset complet */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            margin: 0;
            padding: 0;
            width: 85mm;
            height: 54mm;
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
        }
        
        /* Container principal du badge */
        .badge-container {
            width: 85mm;
            height: 54mm;
            position: relative;
            overflow: hidden;
            background: #ffffff;
        }
        
        /* ========================================
           HEADER DU BADGE
           ======================================== */
        .badge-header {
            width: 100%;
            height: 14mm;
            background-color: #1e3a8a;
            text-align: center;
            padding-top: 2.5mm;
            position: absolute;
            top: 0;
            left: 0;
        }
        
        /* Couleurs selon le type */
        .badge-header.type-head {
            background: linear-gradient(135deg, #991b1b 0%, #7f1d1d 100%);
        }
        
        .badge-header.type-vip {
            background: linear-gradient(135deg, #854d0e 0%, #713f12 100%);
        }
        
        .badge-header.type-staff {
            background: linear-gradient(135deg, #166534 0%, #14532d 100%);
        }
        
        .badge-header.type-member {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
        }
        
        .badge-logo {
            height: 6mm;
            vertical-align: middle;
        }
        
        .badge-org-title {
            display: block;
            color: #ffffff;
            font-size: 6.5pt;
            font-weight: bold;
            letter-spacing: 0.3px;
            margin-top: 0.8mm;
            text-transform: uppercase;
        }
        
        /* ========================================
           CORPS DU BADGE
           ======================================== */
        .badge-body {
            width: 100%;
            position: absolute;
            top: 14mm;
            left: 0;
            height: 34mm;
            text-align: center;
            padding: 0.5mm 3mm 1mm 3mm;
            overflow: hidden;
        }
        
        .participant-name {
            font-size: 11pt;
            font-weight: bold;
            color: #1e293b;
            line-height: 1.15;
            margin-bottom: 0.5mm;
            max-height: 7mm;
            overflow: hidden;
        }
        
        /* Couleur du nom selon le type */
        .type-head .participant-name {
            color: #991b1b;
        }
        
        .type-vip .participant-name {
            color: #854d0e;
        }
        
        .participant-title {
            font-size: 7pt;
            color: #64748b;
            margin-bottom: 0.5mm;
            max-height: 3.5mm;
            overflow: hidden;
        }
        
        .participant-organization {
            font-size: 8pt;
            color: #334155;
            font-weight: 600;
            margin-bottom: 1mm;
            max-height: 4mm;
            overflow: hidden;
        }
        
        /* Rôle/Fonction dans un badge coloré */
        .badge-role {
            display: inline-block;
            font-size: 6.5pt;
            padding: 0.8mm 2.5mm;
            border-radius: 1.5mm;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.2px;
        }
        
        .type-head .badge-role {
            background-color: #fecaca;
            color: #991b1b;
        }
        
        .type-vip .badge-role {
            background-color: #fef3c7;
            color: #854d0e;
        }
        
        .type-staff .badge-role {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .type-member .badge-role {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        /* Informations sur la réunion */
        .meeting-info {
            font-size: 6pt;
            color: #1e293b;
            font-weight: bold;
            margin-top: 0.8mm;
            padding-top: 0.8mm;
            border-top: 0.2mm dashed #cbd5e1;
            line-height: 1.3;
            max-height: 8mm;
            overflow: hidden;
        }
        
        /* ========================================
           FOOTER DU BADGE
           ======================================== */
        .badge-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 6mm;
            background-color: #f1f5f9;
            text-align: center;
            padding-top: 1.5mm;
            font-size: 5pt;
            color: #64748b;
            border-top: 0.2mm solid #e2e8f0;
        }
    </style>
</head>
<body>
@php
    // Chemin du logo CEEAC
    $logoPath = public_path('images/logo-ceeac.png');
    if (!file_exists($logoPath)) {
        $alternatives = glob(public_path('images/*ceeac*.png'));
        $logoPath = !empty($alternatives) ? $alternatives[0] : null;
    }
    
    // Labels des rôles en français
    $roleLabels = [
        'head' => 'CHEF DE DÉLÉGATION',
        'deputy' => 'CHEF ADJOINT',
        'member' => 'MEMBRE',
        'advisor' => 'CONSEILLER',
        'expert' => 'EXPERT',
        'observer' => 'OBSERVATEUR',
        'interpreter' => 'INTERPRÈTE',
        'secretary' => 'SECRÉTAIRE',
        'staff' => 'PERSONNEL',
        'organizer' => 'ORGANISATEUR',
        'vip' => 'VIP',
        'minister' => 'MINISTRE',
        'ambassador' => 'AMBASSADEUR',
    ];
    
    // Déterminer le type de badge selon le rôle
    $role = strtolower($participant->role ?? 'member');
    $badgeType = 'member';
    if (in_array($role, ['head', 'president', 'chairman', 'chef'])) {
        $badgeType = 'head';
    } elseif (in_array($role, ['vip', 'minister', 'ambassador', 'ministre', 'ambassadeur'])) {
        $badgeType = 'vip';
    } elseif (in_array($role, ['staff', 'organizer', 'secretariat', 'personnel'])) {
        $badgeType = 'staff';
    }
    
    // Construire le nom complet
    $fullName = '';
    if (!empty($participant->title)) {
        $fullName .= $participant->title . ' ';
    }
    if (!empty($participant->first_name)) {
        $fullName .= $participant->first_name . ' ';
    }
    if (!empty($participant->last_name)) {
        $fullName .= $participant->last_name;
    }
    $fullName = trim($fullName);
    
    // Fallback sur full_name ou name
    if (empty($fullName)) {
        $fullName = $participant->full_name ?? $participant->name ?? 'Participant';
    }
    
    // Organisation/Délégation
    $organizationName = '';
    if (isset($delegation)) {
        $organizationName = $delegation->title ?? $delegation->country ?? $delegation->organization_name ?? '';
    } elseif (isset($participant->delegation)) {
        $organizationName = $participant->delegation->title ?? $participant->delegation->country ?? '';
    }
    
    // Position/Fonction
    $position = $participant->position ?? '';
@endphp

<div class="badge-container type-{{ $badgeType }}">
    {{-- En-tête avec logo --}}
    <div class="badge-header type-{{ $badgeType }}">
        @if($logoPath && file_exists($logoPath))
            <img src="{{ $logoPath }}" alt="CEEAC" class="badge-logo">
        @endif
        <span class="badge-org-title">CEEAC – Réunion Statutaire</span>
    </div>
    
    {{-- Corps du badge --}}
    <div class="badge-body">
        <div class="participant-name">{{ $fullName }}</div>
        
        @if(!empty($position))
            <div class="participant-title">{{ $position }}</div>
        @endif
        
        @if(!empty($organizationName))
            <div class="participant-organization">{{ $organizationName }}</div>
        @endif
        
        <div class="badge-role">
            {{ $roleLabels[$role] ?? strtoupper(str_replace('_', ' ', $role)) }}
        </div>
        
        @if(isset($meeting) && $meeting)
            <div class="meeting-info">
                {{ \Illuminate\Support\Str::limit($meeting->title ?? '', 80) }}
                @if($meeting->start_at)
                    <br>{{ $meeting->start_at->format('d/m/Y') }}
                @endif
            </div>
        @endif
    </div>
    
    {{-- Pied de page --}}
    <div class="badge-footer">
        SGRS-CEEAC • Ce badge doit être porté de manière visible
    </div>
</div>
</body>
</html>
