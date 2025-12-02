@extends('layouts.app')

@section('title', 'Demandes de réunion')

@section('content')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
        <li class="breadcrumb-item active">Demandes de réunion</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold">Demandes de réunion</h3>
        <p class="text-muted mb-0 small">Accueil / Demandes de réunion</p>
    </div>
    @can('create', App\Models\MeetingRequest::class)
    <a href="{{ route('meeting-requests.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Nouvelle demande
    </a>
    @endcan
</div>

@include('partials.alerts')

{{-- Filtres --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('meeting-requests.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small">Recherche</label>
                <input type="text" name="q" class="form-control" value="{{ $search }}" placeholder="Titre ou description">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Statut</label>
                <select name="status" class="form-select">
                    <option value="">Tous</option>
                    <option value="pending" @selected($status === 'pending')>En attente</option>
                    <option value="approved" @selected($status === 'approved')>Approuvées</option>
                    <option value="rejected" @selected($status === 'rejected')>Rejetées</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="bi bi-search me-1"></i> Filtrer
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Liste des demandes --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Titre</th>
                        <th>Type</th>
                        <th>Date demandée</th>
                        <th>Demandeur</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $request)
                        <tr>
                            <td>
                                <a href="{{ route('meeting-requests.show', $request) }}" class="text-decoration-none fw-semibold">
                                    {{ $request->title }}
                                </a>
                            </td>
                            <td>{{ $request->meetingType->name ?? '—' }}</td>
                            <td>{{ $request->requested_start_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $request->requester->name }}</td>
                            <td>
                                @php
                                    $statusClass = match($request->status) {
                                        'pending' => 'bg-warning text-dark',
                                        'approved' => 'bg-success',
                                        'rejected' => 'bg-danger',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ ucfirst($request->status) }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('meeting-requests.show', $request) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                Aucune demande trouvée.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($requests->hasPages())
        <div class="modern-card-footer">
            <div class="small text-muted">
                Affichage de {{ $requests->firstItem() }} à {{ $requests->lastItem() }} 
                sur {{ $requests->total() }} demande{{ $requests->total() > 1 ? 's' : '' }}
            </div>
            <div class="pagination-modern">
                {{ $requests->appends(request()->query())->links() }}
            </div>
        </div>
    @endif
</div>
@endsection

