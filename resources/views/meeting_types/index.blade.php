@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Types de réunions</h4>
        <div class="small">
            <a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Accueil</a>
            <span class="text-muted">/</span>
            <span class="text-muted">Types de réunions</span>
        </div>
        <p class="text-muted mb-0 mt-1">Paramétrage des catégories de réunions statutaires (CCE, CDM, etc.).</p>
    </div>
    @can('create', App\Models\MeetingType::class)
    <a href="{{ route('meeting-types.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Nouveau type
    </a>
    @endcan
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('meeting-types.index') }}" class="row g-2 align-items-end">
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
                        <th>Approbations</th>
                        <th>Ordre</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($types as $type)
                    <tr>
                        <td>
                            <a href="{{ route('meeting-types.show', $type) }}" class="fw-semibold text-decoration-none">
                                {{ $type->name }}
                            </a>
                            @if($type->description)
                                <div class="small text-muted">
                                    {{ \Illuminate\Support\Str::limit($type->description, 80) }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $type->code }}
                            </span>
                        </td>
                        <td class="small">
                            @if($type->requires_president_approval)
                                <span class="badge bg-outline border border-danger text-danger mb-1">
                                    Présidence
                                </span>
                            @endif
                            @if($type->requires_sg_approval)
                                <span class="badge bg-outline border border-primary text-primary mb-1">
                                    Secrétariat Général
                                </span>
                            @endif
                            @if(!$type->requires_president_approval && !$type->requires_sg_approval)
                                <span class="text-muted">Aucune</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-secondary-subtle text-secondary-emphasis">
                                {{ $type->sort_order }}
                            </span>
                        </td>
                        <td>
                            @if($type->is_active)
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
                            @can('update', $type)
                            <a href="{{ route('meeting-types.edit', $type) }}"
                               class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @endcan
                            @can('delete', $type)
                            <form action="{{ route('meeting-types.destroy', $type) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Supprimer ce type de réunion ?');">
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
                            Aucun type de réunion défini pour le moment.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($types->hasPages())
            <div class="modern-card-footer">
                <div class="small text-muted">
                    Affichage de {{ $types->firstItem() }} à {{ $types->lastItem() }} 
                    sur {{ $types->total() }} type{{ $types->total() > 1 ? 's' : '' }} de réunion
                </div>
                <div class="pagination-modern">
                    {{ $types->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
