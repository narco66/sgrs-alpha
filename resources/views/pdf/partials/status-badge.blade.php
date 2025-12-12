{{-- 
    BADGE DE STATUT PDF
    Usage: @include('pdf.partials.status-badge', ['status' => 'planifiee', 'type' => 'meeting'])
    
    Types supportés: meeting, delegation, terms, participant
--}}
@php
    $statusMappings = [
        'meeting' => [
            'brouillon' => ['label' => 'Brouillon', 'class' => 'badge-secondary'],
            'planifiee' => ['label' => 'Planifiée', 'class' => 'badge-primary'],
            'en_preparation' => ['label' => 'En préparation', 'class' => 'badge-warning'],
            'en_cours' => ['label' => 'En cours', 'class' => 'badge-info'],
            'terminee' => ['label' => 'Terminée', 'class' => 'badge-success'],
            'annulee' => ['label' => 'Annulée', 'class' => 'badge-danger'],
        ],
        'delegation' => [
            'invited' => ['label' => 'Invité', 'class' => 'badge-warning'],
            'confirmed' => ['label' => 'Confirmé', 'class' => 'badge-success'],
            'registered' => ['label' => 'Inscrit', 'class' => 'badge-info'],
            'present' => ['label' => 'Présent', 'class' => 'badge-primary'],
            'absent' => ['label' => 'Absent', 'class' => 'badge-danger'],
            'excused' => ['label' => 'Excusé', 'class' => 'badge-secondary'],
        ],
        'terms' => [
            'draft' => ['label' => 'Brouillon', 'class' => 'badge-secondary'],
            'pending_validation' => ['label' => 'En validation', 'class' => 'badge-warning'],
            'validated' => ['label' => 'Validé', 'class' => 'badge-info'],
            'signed' => ['label' => 'Signé', 'class' => 'badge-success'],
            'cancelled' => ['label' => 'Annulé', 'class' => 'badge-danger'],
        ],
        'participant' => [
            'pending' => ['label' => 'En attente', 'class' => 'badge-warning'],
            'confirmed' => ['label' => 'Confirmé', 'class' => 'badge-success'],
            'present' => ['label' => 'Présent', 'class' => 'badge-primary'],
            'absent' => ['label' => 'Absent', 'class' => 'badge-danger'],
            'excused' => ['label' => 'Excusé', 'class' => 'badge-secondary'],
        ],
    ];
    
    $statusValue = $status ?? 'unknown';
    if (is_object($statusValue) && property_exists($statusValue, 'value')) {
        $statusValue = $statusValue->value;
    }
    
    $typeMapping = $statusMappings[$type ?? 'meeting'] ?? $statusMappings['meeting'];
    $statusInfo = $typeMapping[$statusValue] ?? ['label' => ucfirst($statusValue), 'class' => 'badge-secondary'];
@endphp

<span class="badge {{ $statusInfo['class'] }}">{{ $statusInfo['label'] }}</span>












