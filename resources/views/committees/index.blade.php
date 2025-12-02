@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Comités</h4>
        <div class="small">
            <a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Accueil</a>
            <span class="text-muted">/</span>
            <span class="text-muted">Comités</span>
        </div>
        <p class="text-muted mb-0 mt-1">Paramétrage des comités et groupes de travail liés aux réunions.</p>
    </div>
    <a href="{{ route('committees.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Nouveau comité
    </a>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('committees.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small">Recherche</label>
                <input type="text" name="q" class="form-control"
                       value="{{ $search }}" placeholder="Nom ou code">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="bi bi-search me-1"></i> Rechercher
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small text-muted">
                    <tr>
                        <th>Nom</th>
                        <th>Code</th>
                        <th>Type de réunion associé</th>
                        <th>Nature</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($committees as $committee)
                    <tr>
                        <td>
                            <a href="{{ route('committees.show', $committee) }}"
                               class="fw-semibold text-decoration-none">
                                {{ $committee->name }}
                            </a>
                            @if($committee->description)
                                <div class="small text-muted">
                                    {{ \Illuminate\Support\Str::limit($committee->description, 80) }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $committee->code }}
                            </span>
                        </td>
                        <td>
                            @if($committee->meetingType)
                                <span class="badge bg-primary-subtle text-primary-emphasis">
                                    {{ $committee->meetingType->name }}
                                </span>
                            @else
                                <span class="text-muted small">Non défini</span>
                            @endif
                        </td>
                        <td>
                            @if($committee->is_permanent)
                                <span class="badge bg-info-subtle text-info-emphasis">
                                    Permanent
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary-emphasis">
                                    Ad hoc
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($committee->is_active)
                                <span class="badge bg-success-subtle text-success-emphasis">
                                    Actif
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary-emphasis">
                                    Inactif
                                </span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('committees.edit', $committee) }}"
                               class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('committees.destroy', $committee) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Supprimer ce comité ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Aucun comité défini pour le moment.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
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
</div>
@endsection
