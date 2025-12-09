@extends('layouts.app')

@section('title', 'Salles de réunion')

@section('content')
{{-- Fil d’Ariane --}}
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">Accueil</a>
        </li>
        <li class="breadcrumb-item active">Salles de réunion</li>
    </ol>
</nav>

{{-- En-tête de page --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="page-title mb-1">Salles de réunion</h3>
        <p class="text-muted mb-0 small">Configuration des réunions / Salles</p>
    </div>

    @can('create', \App\Models\Room::class)
        <a href="{{ route('rooms.create') }}" class="btn btn-modern btn-modern-primary">
            <i class="bi bi-plus-circle"></i>
            Nouvelle salle
        </a>
    @endcan
</div>

@if(session('success'))
    <x-modern-alert type="success" dismissible>
        {{ session('success') }}
    </x-modern-alert>
@elseif(session('error'))
    <x-modern-alert type="danger" dismissible>
        {{ session('error') }}
    </x-modern-alert>
@endif

{{-- Statistiques rapides --}}
@php
    $filter = $filter ?? 'all';
@endphp
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="modern-card h-100">
            <div class="modern-card-body d-flex align-items-center">
                <div class="kpi-icon" style="background: linear-gradient(135deg, rgba(102,126,234,0.08) 0%, rgba(118,75,162,0.12) 100%);">
                    <i class="bi bi-building text-primary"></i>
                </div>
                <div class="ms-3">
                    <div class="kpi-value mb-0">{{ $stats['total'] ?? 0 }}</div>
                    <div class="kpi-label">Salle{{ ($stats['total'] ?? 0) > 1 ? 's' : '' }} au total</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="modern-card h-100">
            <div class="modern-card-body d-flex align-items-center">
                <div class="kpi-icon" style="background: linear-gradient(135deg, rgba(67,233,123,0.08) 0%, rgba(56,249,215,0.12) 100%);">
                    <i class="bi bi-check-circle text-success"></i>
                </div>
                <div class="ms-3">
                    <div class="kpi-value mb-0">{{ $stats['available'] ?? 0 }}</div>
                    <div class="kpi-label">Salles disponibles</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="modern-card h-100">
            <div class="modern-card-body d-flex align-items-center">
                <div class="kpi-icon" style="background: linear-gradient(135deg, rgba(240,147,251,0.08) 0%, rgba(245,87,108,0.12) 100%);">
                    <i class="bi bi-clock-history text-warning"></i>
                </div>
                <div class="ms-3">
                    <div class="kpi-value mb-0">{{ $stats['occupied'] ?? 0 }}</div>
                    <div class="kpi-label">Salles occupées</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filtres modernes --}}
<div class="modern-filters mb-4">
    <div class="modern-filters-header">
        <h5 class="modern-filters-title">
            <i class="bi bi-funnel"></i>
            Filtres de recherche
        </h5>
        @if(($filters['q'] ?? null) || ($filters['capacity'] ?? null) || $filter !== 'all')
            <a href="{{ route('rooms.index') }}" class="btn btn-sm btn-modern btn-modern-secondary">
                <i class="bi bi-x-lg me-1"></i>
                Réinitialiser
            </a>
        @endif
    </div>

    <form method="GET" action="{{ route('rooms.index') }}" class="row g-3 align-items-end">
        {{-- Recherche texte --}}
        <div class="col-md-4">
            <label class="form-label">
                <i class="bi bi-search"></i>
                Recherche
            </label>
            <input type="text"
                   name="q"
                   class="form-control"
                   value="{{ $filters['q'] ?? '' }}"
                   placeholder="Nom, code, localisation...">
        </div>

        {{-- Capacité minimale --}}
        <div class="col-md-3">
            <label class="form-label">
                <i class="bi bi-people"></i>
                Capacité minimale
            </label>
            <div class="input-group">
                <input type="number"
                       name="capacity"
                       class="form-control"
                       value="{{ $filters['capacity'] ?? '' }}"
                       min="1"
                       placeholder="Ex : 20">
                <span class="input-group-text">pers.</span>
            </div>
        </div>

        {{-- Boutons de disponibilité --}}
        <div class="col-md-5">
            <label class="form-label">
                <i class="bi bi-circle-half"></i>
                Disponibilité
            </label>
            <div class="btn-group w-100" role="group">
                <button type="submit"
                        name="filter"
                        value="all"
                        class="btn btn-modern {{ $filter === 'all' ? 'btn-modern-primary' : 'btn-modern-secondary' }}">
                    <i class="bi bi-grid-3x3-gap me-1"></i>
                    Toutes
                </button>
                <button type="submit"
                        name="filter"
                        value="available"
                        class="btn btn-modern {{ $filter === 'available' ? 'btn-modern-primary' : 'btn-modern-secondary' }}">
                    <i class="bi bi-check-circle me-1"></i>
                    Disponibles
                </button>
                <button type="submit"
                        name="filter"
                        value="occupied"
                        class="btn btn-modern {{ $filter === 'occupied' ? 'btn-modern-primary' : 'btn-modern-secondary' }}">
                    <i class="bi bi-clock-fill me-1"></i>
                    Occupées
                </button>
            </div>
        </div>

        <div class="col-12 d-flex justify-content-end">
            <button type="submit" class="btn btn-modern btn-modern-primary">
                <i class="bi bi-search me-1"></i>
                Appliquer les filtres
            </button>
        </div>
    </form>
</div>

{{-- Liste des salles (cartes) --}}
@if($rooms->count() > 0)
    <div class="row g-4">
        @foreach($rooms as $room)
            @php
                $isOccupied      = $room->is_occupied;
                $currentMeeting  = $room->current_meeting;
                $participants    = 0;

                if ($currentMeeting) {
                    $participants = $currentMeeting->delegations?->sum(function ($d) {
                        return $d->members?->count() ?? 0;
                    }) ?? 0;
                }

                $capacityRatio = $participants > 0 && $room->capacity > 0
                    ? min(100, round($participants * 100 / $room->capacity))
                    : 0;
            @endphp

            <div class="col-xl-4 col-lg-6">
                <div class="modern-card room-card h-100">
                    <div class="modern-card-body">
                        <div class="d-flex gap-3">
                            {{-- Vignette / image de la salle --}}
                            <div class="room-thumb flex-shrink-0">
                                <a href="{{ route('rooms.show', $room) }}" class="d-block h-100">
                                    @if($room->image)
                                        <img src="{{ $room->image_url }}"
                                             alt="{{ $room->name }}"
                                             class="room-thumb-image">
                                    @else
                                        <div class="room-thumb-placeholder">
                                            <i class="bi bi-door-open"></i>
                                        </div>
                                    @endif
                                </a>

                                {{-- Badge statut sur l’image --}}
                                <div class="room-thumb-badge">
                                    @if($isOccupied)
                                        <span class="badge-modern badge-modern-danger">
                                            <i class="bi bi-clock-fill me-1"></i>Occupée
                                        </span>
                                    @elseif(!$room->is_active)
                                        <span class="badge-modern badge-modern-secondary">
                                            <i class="bi bi-pause-circle me-1"></i>Inactive
                                        </span>
                                    @else
                                        <span class="badge-modern badge-modern-success">
                                            <i class="bi bi-check-circle me-1"></i>Disponible
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Informations principales --}}
                            <div class="flex-grow-1 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h5 class="mb-1 fw-semibold">
                                            <a href="{{ route('rooms.show', $room) }}" class="text-decoration-none text-dark">
                                                {{ $room->name }}
                                            </a>
                                        </h5>
                                        @if($room->location)
                                            <div class="small text-muted">
                                                <i class="bi bi-geo-alt me-1"></i>{{ $room->location }}
                                            </div>
                                        @endif
                                    </div>
                                    <span class="badge bg-light text-dark small">
                                        <i class="bi bi-tag me-1"></i>{{ $room->code ?? 'N/A' }}
                                    </span>
                                </div>

                                {{-- Capacité / taux d’occupation --}}
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span class="text-muted">Capacité</span>
                                        <span>
                                            @if($participants > 0)
                                                <strong class="text-primary">{{ $participants }}</strong>
                                                <span class="text-muted">/</span>
                                            @endif
                                            <strong>{{ $room->capacity }}</strong> pers.
                                        </span>
                                    </div>
                                    <div class="progress room-progress">
                                        <div class="progress-bar
                                                    {{ $capacityRatio >= 90 ? 'bg-danger' : ($capacityRatio >= 70 ? 'bg-warning' : 'bg-primary') }}"
                                             role="progressbar"
                                             style="width: {{ $capacityRatio }}%;"
                                             aria-valuenow="{{ $capacityRatio }}"
                                             aria-valuemin="0"
                                             aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>

                                {{-- Équipements (résumé) --}}
                                <div class="mb-2">
                                    @if($room->equipments && count($room->equipments) > 0)
                                        <div class="d-flex flex-wrap gap-1 small">
                                            @foreach(collect($room->equipments_with_labels)->take(4) as $equip)
                                                <span class="badge bg-light text-dark border">
                                                    {{ $equip['label'] }}
                                                </span>
                                            @endforeach
                                            @if(count($room->equipments_with_labels) > 4)
                                                <span class="badge bg-light text-dark border">
                                                    +{{ count($room->equipments_with_labels) - 4 }} autres
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="small text-muted fst-italic">
                                            Aucun équipement renseigné
                                        </span>
                                    @endif
                                </div>

                                {{-- Infos réunion en cours / action --}}
                                <div class="mt-auto pt-2 border-top small">
                                    @if($isOccupied && $currentMeeting)
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="me-2">
                                                <a href="{{ route('meetings.show', $currentMeeting) }}"
                                                   class="text-decoration-none">
                                                    <div class="fw-semibold text-danger">
                                                        {{ \Illuminate\Support\Str::limit($currentMeeting->title, 40) }}
                                                    </div>
                                                </a>
                                                <div class="text-muted">
                                                    <i class="bi bi-clock me-1"></i>
                                                    {{ $currentMeeting->start_at?->format('H:i') }}
                                                    @if($currentMeeting->end_at)
                                                        – {{ $currentMeeting->end_at->format('H:i') }}
                                                    @elseif($currentMeeting->duration_minutes)
                                                        – {{ $currentMeeting->start_at->copy()->addMinutes($currentMeeting->duration_minutes)->format('H:i') }}
                                                    @endif
                                                </div>
                                            </div>
                                            <a href="{{ route('meetings.show', $currentMeeting) }}"
                                               class="btn btn-sm btn-outline-danger">
                                                Voir
                                            </a>
                                        </div>
                                    @else
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted">
                                                <i class="bi bi-calendar-x me-1"></i>
                                                Aucune réunion en cours
                                            </span>
                                            @can('create', \App\Models\Meeting::class)
                                                <a href="{{ route('meetings.create', ['room_id' => $room->id]) }}"
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-plus-circle me-1"></i>
                                                    Planifier
                                                </a>
                                            @endcan
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Pied de carte : actions --}}
                    <div class="modern-card-footer d-flex justify-content-between align-items-center">
                        <span class="small text-muted">
                            Créée le {{ $room->created_at?->format('d/m/Y') ?? 'N/A' }}
                        </span>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('rooms.show', $room) }}"
                               class="btn btn-outline-secondary"
                               title="Voir les détails">
                                <i class="bi bi-eye"></i>
                            </a>
                            @can('update', $room)
                                <a href="{{ route('rooms.edit', $room) }}"
                                   class="btn btn-outline-primary"
                                   title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            @endcan
                            @can('delete', $room)
                                <form action="{{ route('rooms.destroy', $room) }}"
                                      method="POST"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette salle ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-outline-danger"
                                            title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($rooms->hasPages())
        <div class="modern-card mt-4">
            <div class="modern-card-footer d-flex justify-content-between align-items-center">
                <div class="small text-muted">
                    Affichage de {{ $rooms->firstItem() }} à {{ $rooms->lastItem() }}
                    sur {{ $rooms->total() }} salle{{ $rooms->total() > 1 ? 's' : '' }}
                </div>
                <div class="pagination-modern">
                    {{ $rooms->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    @endif
@else
    <div class="modern-card">
        <div class="modern-card-body text-center py-5">
            <div class="empty-state">
                <i class="bi bi-building empty-state-icon"></i>
                <div class="empty-state-title">Aucune salle enregistrée</div>
                <div class="empty-state-text">
                    Créez votre première salle de réunion pour commencer à planifier les sessions.
                </div>
                @can('create', \App\Models\Room::class)
                    <a href="{{ route('rooms.create') }}" class="btn btn-modern btn-modern-primary mt-3">
                        <i class="bi bi-plus-circle me-1"></i>
                        Créer une salle
                    </a>
                @endcan
            </div>
        </div>
    </div>
@endif

@push('styles')
<style>
    .room-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .room-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.12);
    }

    .room-thumb {
        position: relative;
        width: 120px;
        min-width: 120px;
        height: 90px;
        border-radius: 12px;
        overflow: hidden;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    }

    .room-thumb-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .room-thumb-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        font-size: 2rem;
    }

    .room-thumb-badge {
        position: absolute;
        top: 6px;
        left: 6px;
        right: 6px;
        display: flex;
        justify-content: space-between;
        pointer-events: none;
    }

    .room-progress {
        height: 8px;
        border-radius: 999px;
        background-color: #e2e8f0;
    }

    @media (max-width: 575.98px) {
        .room-thumb {
            width: 100px;
            min-width: 100px;
            height: 80px;
        }
    }
</style>
@endpush
@endsection
