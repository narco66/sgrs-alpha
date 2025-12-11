@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Gestion des salles de réunions</h4>
        <div class="small">
            <a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Accueil</a>
            <span class="text-muted">/</span>
            <span class="text-muted">Salles de réunions</span>
        </div>
        <p class="text-muted mb-0 mt-1">Visualisation des disponibilités et des réunions prévues par salle.</p>
    </div>
</div>

@php
    // Valeur courante du filtre :
    // 1) priorité à la variable transmise par le contrôleur ($filter)
    // 2) sinon, lecture du paramètre de requête ?filter=
    // 3) sinon, valeur par défaut : 'all'
    $currentFilter = $filter ?? request('filter', 'all');
@endphp

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body d-flex flex-wrap gap-2">
        <a href="{{ route('rooms.index', ['filter' => 'all']) }}"
           class="btn btn-sm {{ $currentFilter === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
            Toutes les salles
        </a>
        <a href="{{ route('rooms.index', ['filter' => 'available']) }}"
           class="btn btn-sm {{ $currentFilter === 'available' ? 'btn-primary' : 'btn-outline-primary' }}">
            Salles disponibles
        </a>
        <a href="{{ route('rooms.index', ['filter' => 'occupied']) }}"
           class="btn btn-sm {{ $currentFilter === 'occupied' ? 'btn-primary' : 'btn-outline-primary' }}">
            Salles occupées
        </a>
    </div>
</div>

<div class="row g-3">
    @forelse($rooms as $room)
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title mb-0">{{ $room->name }}</h5>
                        @if($room->is_occupied)
                            <span class="badge bg-danger">Occupée</span>
                        @else
                            <span class="badge bg-success">Disponible</span>
                        @endif
                    </div>
                    <p class="text-muted small mb-2">
                        {{ $room->location ?? 'Localisation non renseignée' }}
                    </p>

                    {{-- Capacité et taux d’occupation --}}
                    <div class="mb-3">
                        <div class="d-flex justify-content-between small mb-1">
                            <span>Capacité</span>
                            <span>{{ $room->capacity }} personne{{ $room->capacity > 1 ? 's' : '' }}</span>
                        </div>
                        @php
                            $ratio = 0;
                            $participantsCount = 0;

                            if ($room->is_occupied && $room->current_meeting) {
                                $participantsCount = $room->current_meeting->participants->count() ?? 0;
                                $ratio = min(
                                    100,
                                    round($participantsCount * 100 / max(1, (int) $room->capacity))
                                );
                            }
                        @endphp
                        <div class="progress" style="height:8px;">
                            <div class="progress-bar
                                        @if($ratio < 70) bg-success
                                        @elseif($ratio < 100) bg-warning
                                        @else bg-danger
                                        @endif"
                                 role="progressbar"
                                 style="width: {{ $ratio }}%;"></div>
                        </div>
                    </div>

                    {{-- Équipements --}}
                    <div class="mb-3">
                        <div class="small text-muted mb-1">Équipements présents dans la salle</div>
                        @if($room->equipments && count($room->equipments))
                            @foreach($room->equipments as $equipment)
                                <span class="badge bg-light text-dark border me-1 mb-1">
                                    {{ $equipment }}
                                </span>
                            @endforeach
                        @else
                            <span class="text-muted small">Aucun équipement renseigné.</span>
                        @endif
                    </div>

                    <hr>

                    {{-- Réunion en cours ou prochaine --}}
                    @if($room->is_occupied && $room->current_meeting)
                        @php
                            $meeting   = $room->current_meeting;
                            $startTime = $meeting->start_at?->format('H:i');
                            $endTime   = $meeting->start_at
                                ? $meeting->start_at->copy()->addMinutes($meeting->duration_minutes)->format('H:i')
                                : null;
                            $meetingParticipants = $meeting->participants->count() ?? 0;
                        @endphp

                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold">
                                    {{ $meeting->title }}
                                </div>
                                <div class="text-muted small">
                                    @if($startTime && $endTime)
                                        {{ $startTime }} – {{ $endTime }}
                                    @elseif($startTime)
                                        {{ $startTime }}
                                    @endif
                                    • {{ $meetingParticipants }} personne{{ $meetingParticipants > 1 ? 's' : '' }}
                                </div>
                            </div>
                            <a href="{{ route('meetings.show', $meeting) }}"
                               class="btn btn-sm btn-outline-danger">
                                Voir plus
                            </a>
                        </div>
                    @else
                        <div class="text-center text-muted py-3">
                            <div class="mb-2">
                                <i class="bi bi-calendar-plus fs-2"></i>
                            </div>
                            <div class="fw-semibold mb-1">Aucune réunion prévue dans cette salle</div>
                            <a href="{{ route('meetings.create', ['room_id' => $room->id]) }}"
                               class="btn btn-sm btn-primary">
                                Organiser une réunion dans cette salle
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <p class="text-muted">Aucune salle enregistrée.</p>
    @endforelse
</div>
@endsection
