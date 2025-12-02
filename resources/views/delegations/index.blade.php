@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Délégations</h4>
        <div class="small">
            <a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Accueil</a>
            <span class="text-muted">/</span>
            <a href="{{ route('delegations.index') }}" class="text-decoration-none text-muted">Délégations</a>
        </div>
        <p class="text-muted mb-0 mt-1">Gestion des délégations des États membres de la CEEAC.</p>
    </div>
    @can('create', App\Models\Delegation::class)
    <a href="{{ route('delegations.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Nouvelle délégation
    </a>
    @endcan
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('delegations.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small">Recherche</label>
                <input type="text" name="q" class="form-control"
                       value="{{ $search }}" placeholder="Titre, code ou pays">
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
                        <th>Titre</th>
                        <th>Code</th>
                        <th>Pays</th>
                        <th>Utilisateurs</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($delegations as $delegation)
                    <tr>
                        <td>
                            <a href="{{ route('delegations.show', $delegation) }}"
                               class="fw-semibold text-decoration-none">
                                {{ $delegation->title }}
                            </a>
                            @if($delegation->description)
                                <div class="small text-muted">
                                    {{ \Illuminate\Support\Str::limit($delegation->description, 80) }}
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($delegation->code)
                                <span class="badge bg-light text-dark border">
                                    {{ $delegation->code }}
                                </span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            @if($delegation->country)
                                {{ $delegation->country }}
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info-subtle text-info-emphasis">
                                {{ $delegation->users_count }} utilisateur(s)
                            </span>
                        </td>
                        <td>
                            @if($delegation->is_active)
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
                            <a href="{{ route('delegations.show', $delegation) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                            @can('update', $delegation)
                            <a href="{{ route('delegations.edit', $delegation) }}"
                               class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @endcan
                            @can('delete', $delegation)
                            <form action="{{ route('delegations.destroy', $delegation) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Supprimer cette délégation ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Aucune délégation définie pour le moment.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($delegations->hasPages())
            <div class="modern-card-footer">
                <div class="small text-muted">
                    Affichage de {{ $delegations->firstItem() }} à {{ $delegations->lastItem() }} 
                    sur {{ $delegations->total() }} délégation{{ $delegations->total() > 1 ? 's' : '' }}
                </div>
                <div class="pagination-modern">
                    {{ $delegations->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

