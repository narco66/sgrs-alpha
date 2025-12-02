@extends('layouts.app')

@section('title', 'Réunions statutaires')

@section('content')
{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
        <li class="breadcrumb-item active">Réunions</li>
    </ol>
</nav>

{{-- En-tête de page --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="page-title mb-1">Réunions statutaires</h3>
        <p class="text-muted mb-0 small">Accueil / Réunions</p>
    </div>

    @can('create', \App\Models\Meeting::class)
        <a href="{{ route('meetings.create') }}" class="btn btn-modern btn-modern-primary">
            <i class="bi bi-plus-circle"></i> Nouvelle réunion
        </a>
    @endcan
</div>

@include('partials.alerts')

{{-- STATISTIQUES RAPIDES --}}
@php
    $totalDelegations = $meetings->sum(function ($meeting) {
        return $meeting->delegations()->count();
    });
    $totalMembers = $meetings->sum(function ($meeting) {
        return $meeting->delegations()->withCount('members')->get()->sum('members_count');
    });
    $totalMeetings = $meetings->total();
@endphp

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="modern-card h-100">
            <div class="modern-card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="kpi-icon" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);">
                            <i class="bi bi-calendar-event text-primary"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="kpi-value mb-0">{{ $totalMeetings }}</h5>
                        <small class="kpi-label">Réunion{{ $totalMeetings > 1 ? 's' : '' }} au total</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="modern-card h-100">
            <div class="modern-card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="kpi-icon" style="background: linear-gradient(135deg, rgba(67, 233, 123, 0.1) 0%, rgba(56, 249, 215, 0.1) 100%);">
                            <i class="bi bi-people text-success"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="kpi-value mb-0">{{ $totalDelegations }}</h5>
                        <small class="kpi-label">Délégation{{ $totalDelegations > 1 ? 's' : '' }} total</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="modern-card h-100">
            <div class="modern-card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="kpi-icon" style="background: linear-gradient(135deg, rgba(79, 172, 254, 0.1) 0%, rgba(0, 242, 254, 0.1) 100%);">
                            <i class="bi bi-check-circle text-info"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="kpi-value mb-0">{{ $meetings->where('status', 'terminee')->count() }}</h5>
                        <small class="kpi-label">Réunion{{ $meetings->where('status', 'terminee')->count() > 1 ? 's' : '' }} terminée{{ $meetings->where('status', 'terminee')->count() > 1 ? 's' : '' }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="modern-card h-100">
            <div class="modern-card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="kpi-icon" style="background: linear-gradient(135deg, rgba(240, 147, 251, 0.1) 0%, rgba(245, 87, 108, 0.1) 100%);">
                            <i class="bi bi-clock-history text-warning"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="kpi-value mb-0">{{ $meetings->where('status', 'en_cours')->count() }}</h5>
                        <small class="kpi-label">En cours</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- FILTRES DE RECHERCHE MODERNES --}}
<div class="modern-filters mb-4">
    <div class="modern-filters-header">
        <h5 class="modern-filters-title">
            <i class="bi bi-funnel"></i>
            Filtres de recherche
        </h5>
        @if(collect($filters)->filter()->isNotEmpty())
            <a href="{{ route('meetings.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-x-lg me-1"></i> Réinitialiser
            </a>
        @endif
    </div>
    
    <form method="GET" action="{{ route('meetings.index') }}" class="row g-3">
        <div class="col-md-3">
            <label class="form-label">
                <i class="bi bi-search"></i>
                Recherche
            </label>
            <input type="text" 
                   name="q" 
                   class="form-control" 
                   value="{{ $filters['q'] ?? '' }}" 
                   placeholder="Titre, description, ordre du jour...">
        </div>
        
        <div class="col-md-2">
            <label class="form-label">
                <i class="bi bi-diagram-3"></i>
                Type
            </label>
            <select name="meeting_type_id" class="form-select">
                <option value="">Tous les types</option>
                @foreach($meetingTypes as $type)
                    <option value="{{ $type->id }}" 
                            @selected(($filters['meeting_type_id'] ?? '') == $type->id)>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="col-md-2">
            <label class="form-label">
                <i class="bi bi-people"></i>
                Comité
            </label>
            <select name="committee_id" class="form-select">
                <option value="">Tous les comités</option>
                @foreach($committees as $committee)
                    <option value="{{ $committee->id }}" 
                            @selected(($filters['committee_id'] ?? '') == $committee->id)>
                        {{ $committee->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="col-md-2">
            <label class="form-label">
                <i class="bi bi-info-circle"></i>
                Statut
            </label>
            <select name="status" class="form-select">
                <option value="">Tous les statuts</option>
                <option value="brouillon" @selected(($filters['status'] ?? '') == 'brouillon')>Brouillon</option>
                <option value="planifiee" @selected(($filters['status'] ?? '') == 'planifiee')>Planifiée</option>
                <option value="en_preparation" @selected(($filters['status'] ?? '') == 'en_preparation')>En préparation</option>
                <option value="en_cours" @selected(($filters['status'] ?? '') == 'en_cours')>En cours</option>
                <option value="terminee" @selected(($filters['status'] ?? '') == 'terminee')>Terminée</option>
                <option value="annulee" @selected(($filters['status'] ?? '') == 'annulee')>Annulée</option>
            </select>
        </div>
        
        <div class="col-md-3">
            <label class="form-label">
                <i class="bi bi-calendar-range"></i>
                Période
            </label>
            <div class="d-flex gap-2">
                <input type="date" 
                       name="date_from" 
                       class="form-control" 
                       value="{{ $filters['date_from'] ?? '' }}" 
                       placeholder="Du">
                <input type="date" 
                       name="date_to" 
                       class="form-control" 
                       value="{{ $filters['date_to'] ?? '' }}" 
                       placeholder="Au">
            </div>
        </div>
        
        <div class="col-12">
            <div class="d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-modern btn-modern-primary">
                    <i class="bi bi-search"></i> Appliquer les filtres
                </button>
                @if(collect($filters)->filter()->isNotEmpty())
                    <a href="{{ route('meetings.index') }}" class="btn btn-modern btn-modern-secondary">
                        <i class="bi bi-x-circle"></i> Réinitialiser
                    </a>
                @endif
            </div>
        </div>
    </form>
</div>

{{-- TABLEAU DES RÉUNIONS --}}
<div class="modern-card">
    <div class="modern-card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="modern-card-title">
                <i class="bi bi-list-ul"></i>
                Liste des réunions
            </h5>
            <span class="badge-modern badge-modern-primary">
                {{ $meetings->total() }} résultat{{ $meetings->total() > 1 ? 's' : '' }}
            </span>
        </div>
    </div>
    <div class="modern-card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="sortable" style="width: 30%;">
                            Titre
                            <i class="bi bi-arrow-down-up"></i>
                        </th>
                        <th class="sortable" style="width: 12%;">
                            Type
                            <i class="bi bi-arrow-down-up"></i>
                        </th>
                        <th class="sortable" style="width: 10%;">
                            Statut
                            <i class="bi bi-arrow-down-up"></i>
                        </th>
                        <th class="sortable" style="width: 10%;">
                            Date & Heure
                            <i class="bi bi-arrow-down-up"></i>
                        </th>
                        <th style="width: 8%;">Durée</th>
                        <th style="width: 8%;">Salle</th>
                        <th style="width: 8%;">Participants</th>
                        <th style="width: 14%;" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($meetings as $meeting)
                        <tr>
                            <td>
                                <div class="d-flex align-items-start">
                                    <div class="flex-grow-1">
                                        <a href="{{ route('meetings.show', $meeting) }}" 
                                           class="text-decoration-none fw-semibold text-dark">
                                            {{ $meeting->title }}
                                        </a>
                                        @if($meeting->committee)
                                            <div class="small text-muted mt-1">
                                                <i class="bi bi-people me-1"></i>
                                                {{ $meeting->committee->name }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td>
                                @if($meeting->type && is_object($meeting->type))
                                    <span class="badge-modern badge-modern-info">
                                        {{ $meeting->type->name }}
                                    </span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>

                            <td>
                                @php
                                    $statusConfig = match($meeting->status) {
                                        'brouillon' => ['class' => 'badge-modern-secondary', 'label' => 'Brouillon'],
                                        'planifiee' => ['class' => 'badge-modern-primary', 'label' => 'Planifiée'],
                                        'en_preparation' => ['class' => 'badge-modern-info', 'label' => 'En préparation'],
                                        'en_cours' => ['class' => 'badge-modern-warning', 'label' => 'En cours'],
                                        'terminee' => ['class' => 'badge-modern-success', 'label' => 'Clôturée'],
                                        'annulee' => ['class' => 'badge-modern-danger', 'label' => 'Annulée'],
                                        'scheduled' => ['class' => 'badge-modern-primary', 'label' => 'Planifiée'],
                                        'ongoing' => ['class' => 'badge-modern-warning', 'label' => 'En cours'],
                                        'completed' => ['class' => 'badge-modern-success', 'label' => 'Clôturée'],
                                        default => ['class' => 'badge-modern-secondary', 'label' => ucfirst($meeting->status ?? 'N/A')],
                                    };
                                @endphp
                                <span class="badge-modern {{ $statusConfig['class'] }}">
                                    {{ $statusConfig['label'] }}
                                </span>
                            </td>

                            <td>
                                <div class="small">
                                    <div class="fw-semibold">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        {{ $meeting->start_at?->format('d/m/Y') }}
                                    </div>
                                    <div class="text-muted">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ $meeting->start_at?->format('H:i') }}
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="badge-modern badge-modern-secondary">
                                    {{ $meeting->duration_minutes ?? 0 }} min
                                </span>
                            </td>

                            <td>
                                @if($meeting->room && is_object($meeting->room))
                                    <span class="small">
                                        <i class="bi bi-geo-alt me-1"></i>
                                        {{ Str::limit($meeting->room->name, 20) }}
                                    </span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex flex-column">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-building text-muted me-1"></i>
                                        <span class="fw-semibold">{{ $meeting->delegations_count ?? 0 }}</span>
                                    </div>
                                    @php
                                        $membersCount = 0;
                                        if ($meeting->relationLoaded('delegations')) {
                                            $membersCount = $meeting->delegations->sum(function($d) {
                                                return $d->members_count ?? 0;
                                            });
                                        }
                                    @endphp
                                    @if($membersCount > 0)
                                        <small class="text-muted">{{ $membersCount }} membre{{ $membersCount > 1 ? 's' : '' }}</small>
                                    @endif
                                </div>
                            </td>

                            <td class="text-end">
                                <div class="d-flex align-items-center justify-content-end gap-2">
                                    <a href="{{ route('meetings.show', $meeting) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       data-bs-toggle="tooltip"
                                       title="Voir les détails">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @can('update', $meeting)
                                        <a href="{{ route('meetings.edit', $meeting) }}"
                                           class="btn btn-sm btn-outline-secondary"
                                           data-bs-toggle="tooltip"
                                           title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $meeting)
                                        <form action="{{ route('meetings.destroy', $meeting) }}" 
                                              method="POST" 
                                              class="d-inline m-0"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette réunion ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="tooltip"
                                                    title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="bi bi-inbox empty-state-icon"></i>
                                    <div class="empty-state-title">Aucune réunion trouvée</div>
                                    <div class="empty-state-text">
                                        @if(collect($filters)->filter()->isNotEmpty())
                                            Aucune réunion ne correspond à vos critères de recherche.
                                        @else
                                            Aucune réunion enregistrée pour le moment.
                                        @endif
                                    </div>
                                    @if(collect($filters)->filter()->isNotEmpty())
                                        <a href="{{ route('meetings.index') }}" class="btn btn-modern btn-modern-primary mt-3">
                                            <i class="bi bi-x-circle"></i> Réinitialiser les filtres
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- PAGINATION --}}
@if($meetings->hasPages())
    <div class="modern-card mt-4">
        <div class="modern-card-footer">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="small text-muted">
                    <i class="bi bi-info-circle me-1"></i>
                    Affichage de <strong>{{ $meetings->firstItem() }}</strong> à <strong>{{ $meetings->lastItem() }}</strong> 
                    sur <strong>{{ $meetings->total() }}</strong> réunion{{ $meetings->total() > 1 ? 's' : '' }}
                </div>
                <div class="pagination-modern">
                    {{ $meetings->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

@push('styles')
<style>
    /* Alignement des boutons d'action dans le tableau */
    .table td .d-flex {
        min-height: 38px;
    }

    .table td form {
        margin: 0;
        display: inline-flex;
        align-items: center;
    }

    .table td .btn {
        min-width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.375rem 0.75rem;
    }

    /* Pagination améliorée */
    .pagination-modern {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .pagination-modern .pagination {
        margin: 0;
        gap: 0.25rem;
    }

    .pagination-modern .page-link {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        color: #64748b;
        padding: 0.5rem 0.75rem;
        transition: all 0.2s ease;
    }

    .pagination-modern .page-link:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: #667eea;
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .pagination-modern .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: #667eea;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .pagination-modern .page-item.disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>
@endpush
