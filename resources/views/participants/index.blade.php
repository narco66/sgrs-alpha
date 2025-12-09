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

{{-- En-tete de page --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="page-title mb-1">Participants</h3>
        <p class="text-muted mb-0 small">Accueil / Participants</p>
    </div>
    <a href="{{ route('meetings.index') }}" class="btn btn-modern btn-modern-secondary">
        <i class="bi bi-calendar3"></i>
        Gérer les participants par réunion
    </a>
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
                <input type="text" name="q" class="form-control" value="{{ $search ?? '' }}" placeholder="Nom, email, service">
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
            <div class="col-md-3">
                <label class="form-label">Statut</label>
                <select name="status" class="form-select">
                    <option value="all" @selected(($status ?? 'all') === 'all')>Tous</option>
                    <option value="active" @selected(($status ?? '') === 'active')>Actifs</option>
                    <option value="inactive" @selected(($status ?? '') === 'inactive')>Inactifs</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
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
                        <th>Participant</th>
                        <th>Email</th>
                        <th>Service</th>
                        <th>Statut</th>
                        <th>Réunions</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($participants as $participant)
                    @php
                        $displayName = $participant->name
                            ?: trim(($participant->first_name ?? '') . ' ' . ($participant->last_name ?? ''));
                    @endphp
                    <tr>
                        <td class="fw-semibold">{{ $displayName ?: 'Utilisateur' }}</td>
                        <td>{{ $participant->email ?? 'N/A' }}</td>
                        <td>{{ $participant->service ?? 'Non renseigné' }}</td>
                        <td>
                            @if($participant->is_active)
                                <span class="badge-modern badge-modern-success">Actif</span>
                            @else
                                <span class="badge-modern badge-modern-secondary">Inactif</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark">{{ $participant->meetings_count ?? 0 }}</span>
                            @if(($participant->meetingParticipations ?? null)?->isNotEmpty())
                                <div class="small text-muted mt-1">
                                    @foreach(($participant->meetingParticipations->take(3) ?? []) as $mp)
                                        <div>- {{ $mp->meeting?->title ?? 'Réunion' }} ({{ $mp->meeting?->start_at?->format('d/m') }})</div>
                                    @endforeach
                                    @if(($participant->meetingParticipations->count() ?? 0) > 3)
                                        <div class="text-muted">+ {{ $participant->meetingParticipations->count() - 3 }} autres...</div>
                                    @endif
                                </div>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="table-actions">
                                <a href="{{ route('users.show', $participant) }}"
                                   class="btn btn-sm btn-outline-secondary"
                                   data-bs-toggle="tooltip"
                                   title="Voir la fiche utilisateur">
                                    <i class="bi bi-person"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi bi-inbox empty-state-icon"></i>
                                <div class="empty-state-title">Aucun participant</div>
                                <div class="empty-state-text">Aucun participant relié à une réunion pour le moment.</div>
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
