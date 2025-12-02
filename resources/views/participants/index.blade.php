@extends('layouts.app')

@section('title', 'Participants')

@section('content')
{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
        <li class="breadcrumb-item active">Participants</li>
    </ol>
</nav>

{{-- En-tête de page --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="page-title mb-1">Participants</h3>
        <p class="text-muted mb-0 small">Accueil / Participants</p>
    </div>
    @can('create', \App\Models\Participant::class)
        <a href="{{ route('participants.create') }}" class="btn btn-modern btn-modern-primary">
            <i class="bi bi-person-plus"></i>
            Nouveau participant
        </a>
    @endcan
</div>

@if(session('success'))
    <x-modern-alert type="success" dismissible>
        {{ session('success') }}
    </x-modern-alert>
@endif

{{-- Filtres --}}
<div class="modern-card mb-3">
    <div class="modern-card-header">
        <h5 class="modern-card-title">
            <i class="bi bi-funnel"></i>
            Filtres
        </h5>
    </div>
    <div class="modern-card-body">
        <form method="GET" action="{{ route('participants.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Recherche</label>
                <input type="text" name="q" class="form-control" value="{{ $search ?? '' }}" placeholder="Nom, email, institution">
            </div>
            <div class="col-md-3">
                <label class="form-label">Réunion</label>
                <select name="meeting_id" class="form-select">
                    <option value="">Toutes</option>
                    @foreach($meetings ?? [] as $meeting)
                        <option value="{{ $meeting->id }}" @selected(($meetingId ?? '') == $meeting->id)>
                            {{ $meeting->title }} - {{ $meeting->start_at?->format('d/m') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Statut</label>
                <select name="status" class="form-select">
                    <option value="all" @selected(($status ?? 'all') === 'all')>Tous</option>
                    <option value="active" @selected(($status ?? '') === 'active')>Actifs</option>
                    <option value="inactive" @selected(($status ?? '') === 'inactive')>Inactifs</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Type</label>
                <select name="type" class="form-select">
                    <option value="all" @selected(($type ?? 'all') === 'all')>Tous</option>
                    <option value="internal" @selected(($type ?? '') === 'internal')>Interne</option>
                    <option value="external" @selected(($type ?? '') === 'external')>Externe</option>
                </select>
            </div>
            <div class="col-md-1 d-flex gap-2">
                <button type="submit" class="btn btn-modern btn-modern-primary w-100" title="Appliquer les filtres">
                    <i class="bi bi-search"></i>
                </button>
                <a href="{{ route('participants.index') }}" class="btn btn-modern btn-modern-secondary" title="Réinitialiser">
                    <i class="bi bi-arrow-repeat"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<div class="modern-card">
    <div class="modern-card-header">
        <h5 class="modern-card-title">
            <i class="bi bi-people"></i>
            Liste des participants
        </h5>
        <span class="badge-modern badge-modern-primary">
            {{ $participants->total() }} participant{{ $participants->total() > 1 ? 's' : '' }}
        </span>
    </div>
    <div class="modern-card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Institution</th>
                        <th>Pays</th>
                        <th>Type</th>
                        <th>Statut</th>
                        <th>Réunions</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($participants as $participant)
                    <tr>
                        <td class="fw-semibold">{{ $participant->full_name }}</td>
                        <td>{{ $participant->institution ?? '—' }}</td>
                        <td>{{ $participant->country ?? '—' }}</td>
                        <td>
                            @if($participant->is_internal)
                                <span class="badge-modern badge-modern-primary">Interne</span>
                            @else
                                <span class="badge-modern badge-modern-info">Externe</span>
                            @endif
                        </td>
                        <td>
                            @if($participant->is_active)
                                <span class="badge-modern badge-modern-success">Actif</span>
                            @else
                                <span class="badge-modern badge-modern-secondary">Inactif</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark">{{ $participant->meetings_count ?? 0 }}</span>
                        </td>
                        <td class="text-end">
                            <div class="table-actions">
                                <a href="{{ route('participants.show', $participant) }}"
                                   class="btn btn-sm btn-outline-secondary"
                                   data-bs-toggle="tooltip"
                                   title="Voir les détails">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('participants.edit', $participant) }}"
                                   class="btn btn-sm btn-outline-primary"
                                   data-bs-toggle="tooltip"
                                   title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('participants.destroy', $participant) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Confirmez-vous la suppression de ce participant ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi bi-inbox empty-state-icon"></i>
                                <div class="empty-state-title">Aucun participant</div>
                                <div class="empty-state-text">Aucun participant enregistré pour le moment.</div>
                                <a href="{{ route('participants.index') }}" class="btn btn-modern btn-modern-secondary mt-3">Réinitialiser les filtres</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($participants->hasPages())
            <div class="modern-card-footer">
                <div class="small text-muted">
                    Affichage de {{ $participants->firstItem() }} à {{ $participants->lastItem() }} 
                    sur {{ $participants->total() }} participant{{ $participants->total() > 1 ? 's' : '' }}
                </div>
                <div class="pagination-modern">
                    {{ $participants->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
