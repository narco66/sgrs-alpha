@extends('layouts.app')

@section('title', 'Comités d\'organisation')

@section('content')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
        <li class="breadcrumb-item active">Comités d'organisation</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold">Comités d'organisation</h3>
        <p class="text-muted mb-0 small">Accueil / Comités d'organisation</p>
    </div>
    @can('create', App\Models\OrganizationCommittee::class)
    <a href="{{ route('organization-committees.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Nouveau comité
    </a>
    @endcan
</div>

@include('partials.alerts')

{{-- Filtres --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('organization-committees.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small">Recherche</label>
                <input type="text" name="q" class="form-control" value="{{ $search }}" placeholder="Nom ou description">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="bi bi-search me-1"></i> Rechercher
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Liste des comités --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nom</th>
                        <th>Réunion associée</th>
                        <th>Membres</th>
                        <th>Créé par</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($committees as $committee)
                        <tr>
                            <td>
                                <a href="{{ route('organization-committees.show', $committee) }}" class="text-decoration-none fw-semibold">
                                    {{ $committee->name }}
                                </a>
                                @if($committee->description)
                                    <br><small class="text-muted">{{ Str::limit($committee->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                @if($committee->meeting)
                                    <a href="{{ route('meetings.show', $committee->meeting) }}" class="text-decoration-none">
                                        {{ $committee->meeting->title }}
                                    </a>
                                @else
                                    <span class="text-muted">Aucune</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    <i class="bi bi-people me-1"></i>{{ $committee->members->count() }} membre{{ $committee->members->count() > 1 ? 's' : '' }}
                                </span>
                            </td>
                            <td>{{ $committee->creator->name ?? 'N/A' }}</td>
                            <td>
                                @if($committee->is_active)
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('organization-committees.show', $committee) }}" class="btn btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @can('update', $committee)
                                        <a href="{{ route('organization-committees.edit', $committee) }}" class="btn btn-outline-secondary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $committee)
                                        <form action="{{ route('organization-committees.destroy', $committee) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Êtes-vous sûr ?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                Aucun comité d'organisation trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($committees->hasPages())
        <div class="modern-card-footer">
            <div class="small text-muted">
                Affichage de {{ $committees->firstItem() }} à {{ $committees->lastItem() }} 
                sur {{ $committees->total() }} comité{{ $committees->total() > 1 ? 's' : '' }}
            </div>
            <div class="pagination-modern">
                {{ $committees->appends(request()->query())->links() }}
            </div>
        </div>
    @endif
</div>
@endsection

