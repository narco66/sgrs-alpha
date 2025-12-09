@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    {{-- En-tête --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 fw-bold text-dark">Gestion des salles de réunions</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Accueil</a></li>
                    <li class="breadcrumb-item active text-muted">Salles de réunions</li>
                </ol>
            </nav>
        </div>
        @can('create', App\Models\Room::class)
            <a href="{{ route('rooms.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Nouvelle salle
            </a>
        @endcan
    </div>

    {{-- Messages flash --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filtres --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <a href="{{ route('rooms.index', ['filter' => 'all']) }}"
                   class="btn {{ ($filter ?? 'all') === 'all' ? 'btn-primary' : 'btn-outline-secondary' }}">
                    <i class="bi bi-grid-3x3-gap me-1"></i>
                    Toutes les salles
                    <span class="badge bg-white text-primary ms-1">{{ $stats['total'] ?? 0 }}</span>
                </a>
                <a href="{{ route('rooms.index', ['filter' => 'available']) }}"
                   class="btn {{ ($filter ?? '') === 'available' ? 'btn-success' : 'btn-outline-success' }}">
                    <i class="bi bi-check-circle me-1"></i>
                    Salles disponibles
                    <span class="badge bg-white text-success ms-1">{{ $stats['available'] ?? 0 }}</span>
                </a>
                <a href="{{ route('rooms.index', ['filter' => 'occupied']) }}"
                   class="btn {{ ($filter ?? '') === 'occupied' ? 'btn-danger' : 'btn-outline-danger' }}">
                    <i class="bi bi-clock-fill me-1"></i>
                    Salles occupées
                    <span class="badge bg-white text-danger ms-1">{{ $stats['occupied'] ?? 0 }}</span>
                </a>

                {{-- Recherche --}}
                <div class="ms-auto">
                    <form action="{{ route('rooms.index') }}" method="GET" class="d-flex gap-2">
                        <input type="hidden" name="filter" value="{{ $filter ?? 'all' }}">
                        <div class="input-group" style="width: 250px;">
                            <input type="text" 
                                   name="q" 
                                   class="form-control" 
                                   placeholder="Rechercher une salle..." 
                                   value="{{ $filters['q'] ?? '' }}">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Grille des salles --}}
    <div class="row g-4">
        @forelse($rooms as $room)
            @php
                $isOccupied = $room->is_occupied;
                $currentMeeting = $room->current_meeting;
                $participantsCount = 0;
                
                if ($currentMeeting) {
                    // Compter les participants via les délégations
                    $participantsCount = $currentMeeting->delegations?->sum(function($d) {
                        return $d->members?->count() ?? 0;
                    }) ?? 0;
                }
                
                $capacityRatio = $participantsCount > 0 && $room->capacity > 0 
                    ? min(100, round($participantsCount * 100 / $room->capacity))
                    : 0;
            @endphp
            
            <div class="col-lg-6 col-xl-6">
                <div class="card border-0 shadow-sm h-100 room-card">
                    <div class="card-body p-0">
                        <div class="row g-0">
                            {{-- Image de la salle --}}
                            <div class="col-md-5 position-relative">
                                <div class="room-image-wrapper">
                                    @if($room->image)
                                        <img src="{{ $room->image_url }}" 
                                             alt="{{ $room->name }}" 
                                             class="room-image">
                                    @else
                                        <div class="room-image-placeholder">
                                            <i class="bi bi-door-open fs-1 text-muted"></i>
                                        </div>
                                    @endif
                                    
                                    {{-- Badge de statut --}}
                                    <div class="position-absolute top-0 start-0 m-2">
                                        @if($isOccupied)
                                            <span class="badge bg-danger px-3 py-2 rounded-pill">
                                                <i class="bi bi-clock-fill me-1"></i>Occupée
                                            </span>
                                        @elseif(!$room->is_active)
                                            <span class="badge bg-secondary px-3 py-2 rounded-pill">
                                                <i class="bi bi-pause-circle me-1"></i>Inactive
                                            </span>
                                        @else
                                            <span class="badge bg-success px-3 py-2 rounded-pill">
                                                <i class="bi bi-check-circle me-1"></i>Disponible
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Informations de la salle --}}
                            <div class="col-md-7">
                                <div class="p-3 h-100 d-flex flex-column">
                                    {{-- Titre et localisation --}}
                                    <div class="mb-3">
                                        <h5 class="card-title fw-bold mb-1 text-dark">{{ $room->name }}</h5>
                                        @if($room->location)
                                            <p class="text-muted small mb-0">
                                                <i class="bi bi-geo-alt me-1"></i>{{ $room->location }}
                                            </p>
                                        @endif
                                    </div>
                                    
                                    {{-- Capacité avec barre de progression --}}
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="small text-muted">Capacité</span>
                                            <span class="small">
                                                @if($isOccupied && $participantsCount > 0)
                                                    <span class="fw-semibold text-primary">{{ $participantsCount }}</span>
                                                    <span class="text-muted">/</span>
                                                @endif
                                                <span class="fw-semibold">{{ $room->capacity }}</span>
                                            </span>
                                        </div>
                                        <div class="progress" style="height: 8px; border-radius: 4px;">
                                            <div class="progress-bar {{ $capacityRatio >= 90 ? 'bg-danger' : ($capacityRatio >= 70 ? 'bg-warning' : 'bg-primary') }}" 
                                                 role="progressbar" 
                                                 style="width: {{ $capacityRatio }}%;"
                                                 aria-valuenow="{{ $capacityRatio }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    {{-- Équipements --}}
                                    <div class="mb-3 flex-grow-1">
                                        <div class="small text-muted mb-2">Équipements présent dans la salle</div>
                                        <div class="d-flex flex-wrap gap-1">
                                            @if($room->equipments && count($room->equipments) > 0)
                                                @foreach($room->equipments_with_labels as $equip)
                                                    <span class="badge bg-light text-dark border">
                                                        {{ $equip['label'] }}
                                                    </span>
                                                @endforeach
                                            @else
                                                <span class="text-muted small fst-italic">Aucun équipement renseigné</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    {{-- Séparateur --}}
                                    <hr class="my-2">
                                    
                                    {{-- Réunion en cours ou message --}}
                                    @if($isOccupied && $currentMeeting)
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="flex-grow-1 me-2">
                                                <a href="{{ route('meetings.show', $currentMeeting) }}" 
                                                   class="text-decoration-none">
                                                    <h6 class="mb-1 text-danger fw-semibold">
                                                        {{ Str::limit($currentMeeting->title, 35) }}
                                                    </h6>
                                                </a>
                                                <div class="d-flex align-items-center text-muted small">
                                                    <span class="me-3">
                                                        <i class="bi bi-clock me-1"></i>
                                                        {{ $currentMeeting->start_at?->format('H:i') }}
                                                        @if($currentMeeting->end_at)
                                                            - {{ $currentMeeting->end_at->format('H:i') }}
                                                        @elseif($currentMeeting->duration_minutes)
                                                            - {{ $currentMeeting->start_at->copy()->addMinutes($currentMeeting->duration_minutes)->format('H:i') }}
                                                        @endif
                                                    </span>
                                                    <span>
                                                        <i class="bi bi-people me-1"></i>
                                                        {{ $participantsCount }} Personne{{ $participantsCount > 1 ? 's' : '' }}
                                                    </span>
                                                </div>
                                            </div>
                                            <a href="{{ route('meetings.show', $currentMeeting) }}" 
                                               class="btn btn-danger btn-sm">
                                                Voir Plus
                                            </a>
                                        </div>
                                    @else
                                        <div class="text-center py-2">
                                            <p class="text-muted small mb-2">
                                                <i class="bi bi-calendar-x me-1"></i>
                                                Aucune réunion prévue dans cette salle
                                            </p>
                                            @can('create', App\Models\Meeting::class)
                                                <a href="{{ route('meetings.create', ['room_id' => $room->id]) }}" 
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-plus-circle me-1"></i>
                                                    Organiser une réunion
                                                </a>
                                            @endcan
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Actions au survol --}}
                    <div class="card-footer bg-transparent border-0 py-2 px-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="bi bi-tag me-1"></i>{{ $room->code ?? 'N/A' }}
                            </small>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('rooms.show', $room) }}" 
                                   class="btn btn-outline-secondary" 
                                   title="Voir les détails">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @can('update', $room)
                                    <a href="{{ route('rooms.edit', $room) }}" 
                                       class="btn btn-outline-secondary" 
                                       title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endcan
                                @can('delete', $room)
                                    <form action="{{ route('rooms.destroy', $room) }}" 
                                          method="POST" 
                                          class="d-inline"
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
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="mb-3">
                            <i class="bi bi-door-open text-muted" style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="text-muted mb-2">Aucune salle enregistrée</h5>
                        <p class="text-muted mb-3">Commencez par créer votre première salle de réunion.</p>
                        @can('create', App\Models\Room::class)
                            <a href="{{ route('rooms.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i> Créer une salle
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($rooms->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $rooms->links() }}
        </div>
    @endif
</div>

@push('styles')
<style>
    .room-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        overflow: hidden;
    }
    
    .room-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    
    .room-image-wrapper {
        height: 100%;
        min-height: 200px;
        overflow: hidden;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
    
    .room-image {
        width: 100%;
        height: 100%;
        min-height: 200px;
        object-fit: cover;
    }
    
    .room-image-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        min-height: 200px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
    
    .badge {
        font-weight: 500;
    }
    
    .progress {
        background-color: #e9ecef;
    }
    
    @media (max-width: 767px) {
        .room-image-wrapper {
            min-height: 150px;
        }
        
        .room-image,
        .room-image-placeholder {
            min-height: 150px;
        }
    }
</style>
@endpush
@endsection
