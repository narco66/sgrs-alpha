<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Badge - {{ $participant->full_name ?? $participant->name ?? 'Participant' }}</title>
    <style>
        @page {
            margin: 0;
            size: 85mm 54mm; /* Format carte de visite standard */
        }
        
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        
        .badge-container {
            width: 85mm;
            height: 54mm;
            border: 1px solid #e5e7eb;
            box-sizing: border-box;
            position: relative;
            overflow: hidden;
        }
        
        .badge-header {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: #ffffff;
            padding: 8px 10px;
            text-align: center;
        }
        
        .badge-logo {
            height: 20px;
            vertical-align: middle;
            margin-right: 5px;
        }
        
        .badge-org {
            font-size: 8px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        
        .badge-body {
            padding: 10px;
            text-align: center;
        }
        
        .badge-name {
            font-size: 14px;
            font-weight: bold;
            color: #1e3a8a;
            margin: 8px 0;
            line-height: 1.2;
        }
        
        .badge-title {
            font-size: 9px;
            color: #6b7280;
            margin: 4px 0;
        }
        
        .badge-organization {
            font-size: 10px;
            color: #374151;
            font-weight: 500;
            margin: 6px 0;
        }
        
        .badge-role {
            display: inline-block;
            background: #dbeafe;
            color: #1e40af;
            font-size: 8px;
            padding: 2px 8px;
            border-radius: 10px;
            font-weight: bold;
            margin-top: 5px;
        }
        
        .badge-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: #f3f4f6;
            padding: 5px;
            text-align: center;
            font-size: 7px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
        
        .badge-meeting {
            font-size: 8px;
            color: #4b5563;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px dashed #e5e7eb;
        }
        
        /* Style pour les différents types de participants */
        .badge-type-head .badge-header {
            background: linear-gradient(135deg, #991b1b 0%, #dc2626 100%);
        }
        .badge-type-head .badge-role {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .badge-type-vip .badge-header {
            background: linear-gradient(135deg, #854d0e 0%, #eab308 100%);
        }
        .badge-type-vip .badge-role {
            background: #fef3c7;
            color: #854d0e;
        }
        
        .badge-type-staff .badge-header {
            background: linear-gradient(135deg, #166534 0%, #22c55e 100%);
        }
        .badge-type-staff .badge-role {
            background: #dcfce7;
            color: #166534;
        }
    </style>
</head>
<body>
    @php
        $logoPath = public_path('images/logo-ceeac.png');
        if (!file_exists($logoPath)) {
            $alternatives = glob(public_path('images/*ceeac*.png'));
            $logoPath = $alternatives[0] ?? null;
        }
        
        // Déterminer le type de badge
        $badgeType = 'member';
        $role = $participant->role ?? 'member';
        if (in_array($role, ['head', 'president', 'chairman'])) {
            $badgeType = 'head';
        } elseif (in_array($role, ['vip', 'minister', 'ambassador'])) {
            $badgeType = 'vip';
        } elseif (in_array($role, ['staff', 'organizer', 'secretariat'])) {
            $badgeType = 'staff';
        }
        
        $roleLabels = [
            'head' => 'CHEF DE DÉLÉGATION',
            'deputy' => 'CHEF ADJOINT',
            'member' => 'MEMBRE',
            'advisor' => 'CONSEILLER',
            'expert' => 'EXPERT',
            'interpreter' => 'INTERPRÈTE',
            'staff' => 'STAFF',
            'organizer' => 'ORGANISATEUR',
            'vip' => 'VIP'
        ];
    @endphp

    <div class="badge-container badge-type-{{ $badgeType }}">
        {{-- Header --}}
        <div class="badge-header">
            @if($logoPath && file_exists($logoPath))
                <img src="{{ $logoPath }}" alt="CEEAC" class="badge-logo">
            @endif
            <span class="badge-org">CEEAC - RÉUNION STATUTAIRE</span>
        </div>
        
        {{-- Body --}}
        <div class="badge-body">
            {{-- Nom du participant --}}
            <div class="badge-name">
                @if(isset($participant->title) && $participant->title)
                    {{ $participant->title }}
                @endif
                {{ $participant->first_name ?? '' }} {{ $participant->last_name ?? $participant->name ?? 'Participant' }}
            </div>
            
            {{-- Fonction --}}
            @if(isset($participant->position) && $participant->position)
                <div class="badge-title">{{ $participant->position }}</div>
            @endif
            
            {{-- Organisation/Délégation --}}
            @if(isset($delegation))
                <div class="badge-organization">
                    {{ $delegation->title ?? $delegation->country ?? '' }}
                </div>
            @elseif(isset($participant->delegation))
                <div class="badge-organization">
                    {{ $participant->delegation->title ?? $participant->delegation->country ?? '' }}
                </div>
            @endif
            
            {{-- Rôle --}}
            <div class="badge-role">
                {{ $roleLabels[$role] ?? strtoupper($role) }}
            </div>
            
            {{-- Réunion --}}
            @if(isset($meeting))
                <div class="badge-meeting">
                    {{ Str::limit($meeting->title, 40) }}
                    <br>{{ $meeting->start_at?->format('d/m/Y') ?? '' }}
                </div>
            @endif
        </div>
        
        {{-- Footer --}}
        <div class="badge-footer">
            SGRS-CEEAC | Ce badge doit être porté de manière visible
        </div>
    </div>
</body>
</html>

