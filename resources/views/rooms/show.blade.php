@extends('layouts.app')

@section('title', $room->name . ' – Salle de réunion')

@section('content')
{{-- Fil d’Ariane --}}
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">Accueil</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('rooms.index') }}">Salles de réunion</a>
        </li>
        <li class="breadcrumb-item active">{{ $room->name }}</li>
    </ol>
</nav>

{{-- En-tête --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="d-flex align-items-center mb-1">
            <h3 class="page-title mb-0 me-3">{{ $room->name }}</h3>
            @if($room->is_occupied)
                <span class="badge-modern badge-modern-danger">
                    <i class="bi bi-clock-fill me-1"></i>
                    Occupée
                </span>
            @elseif(!$room->is_active)
                <span class="badge-modern badge-modern-secondary">
                    <i class="bi bi-pause-circle me-1"></i>
                    Inactive
                </span>
            @else
                <span class="badge-modern badge-modern-success">
                    <i class="bi bi-check-circle me-1"></i>
                    Disponible
                </span>
            @endif
        </div>
        <p class="text-muted mb-0 small">
            Fiche détaillée de la salle de réunion et historique d’utilisation.
        </p>
    </div>
    <div class="d-flex gap-2">
        @can('update', $room)
            <a href="{{ route('rooms.edit', $room) }}" class="btn btn-modern btn-modern-primary">
                <i class="bi bi-pencil me-1"></i>
                Modifier
            </a>
        @endcan
        <a href="{{ route('rooms.index') }}" class="btn btn-modern btn-modern-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Retour à la liste
        </a>
    </div>
</div>

{{-- Messages flash --}}
@if(session('success'))
    <x-modern-alert type="success" dismissible>
        {{ session('success') }}
    </x-modern-alert>
@endif

<div class="row g-4">
    {{-- Colonne principale --}}
    <div class="col-lg-8">
        {{-- Carte principale : image + infos --}}
        <div class="modern-card mb-4">
            <div class="modern-card-header">
                <h5 class="modern-card-title">
                    <i class="bi bi-door-open text-primary me-2"></i>
                    Photo et informations principales
                </h5>
            </div>
            <div class="row g-0">
                <div class="col-md-5">
                    <div class="h-100" style="min-height: 260px;">
                        @if($room->image)
                            {{-- Image affichée en taille normale --}}
                            <img src="{{ $room->image_url }}"
                                 alt="{{ $room->name }}"
                                 class="img-fluid h-100 w-100 rounded-start"
                                 style="object-fit: cover;">
                        @else
                            <div class="d-flex align-items-center justify-content-center h-100 bg-light rounded-start">
                                <div class="text-center text-muted">
                                    <i class="bi bi-door-open" style="font-size: 3.5rem;"></i>
                                    <p class="mb-0 mt-2 small">Aucune image enregistrée</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="modern-card-body h-100 d-flex flex-column">
                        <div class="mb-3">
                            <span class="badge bg-light text-dark border mb-2">
                                <i class="bi bi-tag me-1"></i>
                                {{ $room->code ?? 'N/A' }}
                            </span>
                            <h4 class="fw-bold mb-1">{{ $room->name }}</h4>
                            @if($room->location)
                                <p class="text-muted mb-0">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    {{ $room->location }}
                                </p>
                            @endif
                        </div>

                        {{-- Description --}}
                        @if($room->description)
                            <div class="mb-3">
                                <p class="text-muted">{{ $room->description }}</p>
                            </div>
                        @endif

                        {{-- Capacité --}}
                        <div class="mb-3">
                            <div class="d-flex align-items-center">
                                <div class="me-4">
                                    <span class="text-muted small">Capacité</span>
                                    <h3 class="mb-0 text-primary">
                                        {{ $room->capacity }}
                                        <small class="text-muted fs-6">personnes</small>
                                    </h3>
                                </div>
                            </div>
                        </div>

                        {{-- Action rapide --}}
                        <div class="mt-auto">
                            @if($room->is_active && !$room->is_occupied)
                                @can('create', \App\Models\Meeting::class)
                                    <a href="{{ route('meetings.create', ['room_id' => $room->id]) }}"
                                       class="btn btn-modern btn-modern-primary">
                                        <i class="bi bi-plus-circle me-1"></i>
                                        Organiser une réunion
                                    </a>
                                @endcan
                            @elseif($room->is_occupied && $room->current_meeting)
                                <a href="{{ route('meetings.show', $room->current_meeting) }}"
                                   class="btn btn-modern btn-modern-danger">
                                    <i class="bi bi-eye me-1"></i>
                                    Voir la réunion en cours
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Équipements --}}
        <div class="modern-card mb-4">
            <div class="modern-card-header">
                <h5 class="modern-card-title">
                    <i class="bi bi-tools text-primary me-2"></i>
                    Équipements disponibles
                </h5>
            </div>
            <div class="modern-card-body">
                @if($room->equipments && count($room->equipments) > 0)
                    <div class="row g-3">
                        @foreach($room->equipments_with_labels as $equip)
                            <div class="col-md-4 col-sm-6">
                                <div class="d-flex align-items-center p-3 border rounded bg-light">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <span>{{ $equip['label'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-tools fs-1"></i>
                        <p class="mb-0 mt-2">Aucun équipement renseigné pour cette salle.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Réunions à venir --}}
        <div class="modern-card mb-4">
            <div class="modern-card-header d-flex justify-content-between align-items-center">
                <h5 class="modern-card-title mb-0">
                    <i class="bi bi-calendar-event text-primary me-2"></i>
                    Réunions à venir
                </h5>
                @can('create', \App\Models\Meeting::class)
                    <a href="{{ route('meetings.create', ['room_id' => $room->id]) }}"
                       class="btn btn-sm btn-modern btn-modern-secondary">
                        <i class="bi bi-plus"></i>
                        Planifier
                    </a>
                @endcan
            </div>
            <div class="modern-card-body p-0">
                @if($upcomingMeetings->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($upcomingMeetings as $meeting)
                            <a href="{{ route('meetings.show', $meeting) }}"
                               class="list-group-item list-group-item-action py-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1 fw-semibold">{{ $meeting->title }}</h6>
                                        <div class="text-muted small">
                                            <i class="bi bi-calendar me-1"></i>
                                            {{ $meeting->start_at->format('d/m/Y') }}
                                            <i class="bi bi-clock ms-2 me-1"></i>
                                            {{ $meeting->start_at->format('H:i') }}
                                            @if($meeting->duration_minutes)
                                                - {{ $meeting->start_at->copy()->addMinutes($meeting->duration_minutes)->format('H:i') }}
                                            @endif
                                        </div>
                                    </div>
                                    <span class="badge-modern badge-modern-primary">
                                        {{ ucfirst($meeting->status) }}
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-calendar-x fs-1"></i>
                        <p class="mb-0 mt-2">Aucune réunion programmée dans cette salle.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Historique des réunions --}}
        @if($pastMeetings->count() > 0)
            <div class="modern-card">
                <div class="modern-card-header">
                    <h5 class="modern-card-title">
                        <i class="bi bi-clock-history text-primary me-2"></i>
                        Réunions passées
                    </h5>
                </div>
                <div class="modern-card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($pastMeetings as $meeting)
                            <a href="{{ route('meetings.show', $meeting) }}"
                               class="list-group-item list-group-item-action py-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $meeting->title }}</h6>
                                        <div class="text-muted small">
                                            <i class="bi bi-calendar me-1"></i>
                                            {{ $meeting->start_at->format('d/m/Y \à H:i') }}
                                        </div>
                                    </div>
                                    <span class="badge-modern badge-modern-secondary">
                                        {{ ucfirst($meeting->status) }}
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Colonne latérale --}}
    <div class="col-lg-4">
        {{-- Statut actuel --}}
        <div class="modern-card mb-4 {{ $room->is_occupied ? 'border-danger' : ($room->is_active ? 'border-success' : 'border-secondary') }}"
             style="border-width: 2px !important;">
            <div class="modern-card-body text-center py-4">
                @if($room->is_occupied)
                    <div class="text-danger mb-2">
                        <i class="bi bi-clock-fill" style="font-size: 3rem;"></i>
                    </div>
                    <h4 class="text-danger mb-2">Salle occupée</h4>
                    @if($room->current_meeting)
                        <p class="text-muted mb-0">
                            {{ $room->current_meeting->title }}
                        </p>
                        <p class="small text-muted">
                            Jusqu’à
                            {{ $room->current_meeting->end_at?->format('H:i') 
                                ?? $room->current_meeting->start_at?->copy()->addMinutes($room->current_meeting->duration_minutes ?? 60)->format('H:i') }}
                        </p>
                    @endif
                @elseif(!$room->is_active)
                    <div class="text-secondary mb-2">
                        <i class="bi bi-pause-circle" style="font-size: 3rem;"></i>
                    </div>
                    <h4 class="text-secondary mb-2">Salle inactive</h4>
                    <p class="text-muted mb-0">
                        Cette salle n’est pas disponible à la réservation.
                    </p>
                @else
                    <div class="text-success mb-2">
                        <i class="bi bi-check-circle" style="font-size: 3rem;"></i>
                    </div>
                    <h4 class="text-success mb-2">Salle disponible</h4>
                    <p class="text-muted mb-0">
                        Cette salle est libre et peut être réservée.
                    </p>
                @endif
            </div>
        </div>

        {{-- Prochaine réunion --}}
        @if($room->next_meeting && !$room->is_occupied)
            <div class="modern-card mb-4 bg-light">
                <div class="modern-card-body">
                    <h6 class="text-muted mb-3">
                        <i class="bi bi-calendar-check me-1"></i>
                        Prochaine réunion
                    </h6>
                    <h5 class="fw-semibold mb-2">{{ $room->next_meeting->title }}</h5>
                    <div class="text-muted small">
                        <div class="mb-1">
                            <i class="bi bi-calendar me-1"></i>
                            {{ $room->next_meeting->start_at->format('d/m/Y') }}
                        </div>
                        <div>
                            <i class="bi bi-clock me-1"></i>
                            {{ $room->next_meeting->start_at->format('H:i') }}
                        </div>
                    </div>
                    <a href="{{ route('meetings.show', $room->next_meeting) }}"
                       class="btn btn-sm btn-modern btn-modern-secondary mt-3">
                        Voir les détails
                    </a>
                </div>
            </div>
        @endif

        {{-- Informations --}}
        <div class="modern-card mb-4">
            <div class="modern-card-header">
                <h5 class="modern-card-title">
                    <i class="bi bi-info-circle text-primary me-2"></i>
                    Informations
                </h5>
            </div>
            <div class="modern-card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-muted small">Code</div>
                        <div class="fw-semibold">{{ $room->code ?? 'N/A' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">Capacité</div>
                        <div class="fw-semibold">{{ $room->capacity }} personnes</div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted small">Localisation</div>
                        <div class="fw-semibold">{{ $room->location ?? 'Non renseignée' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">Créée le</div>
                        <div class="fw-semibold">{{ $room->created_at?->format('d/m/Y') }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">Modifiée le</div>
                        <div class="fw-semibold">{{ $room->updated_at?->format('d/m/Y') }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="modern-card">
            <div class="modern-card-header">
                <h5 class="modern-card-title">
                    <i class="bi bi-gear text-primary me-2"></i>
                    Actions
                </h5>
            </div>
            <div class="modern-card-body">
                <div class="d-grid gap-2">
                    @can('create', \App\Models\Meeting::class)
                        <a href="{{ route('meetings.create', ['room_id' => $room->id]) }}"
                           class="btn btn-modern btn-modern-primary {{ $room->is_occupied || !$room->is_active ? 'disabled' : '' }}">
                            <i class="bi bi-plus-circle me-1"></i>
                            Planifier une réunion
                        </a>
                    @endcan

                    @can('update', $room)
                        <a href="{{ route('rooms.edit', $room) }}"
                           class="btn btn-modern btn-modern-secondary">
                            <i class="bi bi-pencil me-1"></i>
                            Modifier la salle
                        </a>

                        <form action="{{ route('rooms.toggle-status', $room) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="btn btn-modern btn-modern-secondary w-100">
                                <i class="bi bi-{{ $room->is_active ? 'pause-circle' : 'play-circle' }} me-1"></i>
                                {{ $room->is_active ? 'Désactiver' : 'Activer' }} la salle
                            </button>
                        </form>
                    @endcan

                    @can('delete', $room)
                        <form action="{{ route('rooms.destroy', $room) }}"
                              method="POST"
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette salle ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-modern btn-modern-danger w-100">
                                <i class="bi bi-trash me-1"></i>
                                Supprimer la salle
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
