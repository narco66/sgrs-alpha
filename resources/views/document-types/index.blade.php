@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Types de documents</h4>
        <div class="small">
            <a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Accueil</a>
            <span class="text-muted">/</span>
            <span class="text-muted">Types de documents</span>
        </div>
        <p class="text-muted mb-0 mt-1">Gestion des types de documents du système SGRS-CEEAC.</p>
    </div>
    @can('create', App\Models\DocumentType::class)
    <a href="{{ route('document-types.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Nouveau type
    </a>
    @endcan
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('document-types.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small">Recherche</label>
                <input type="text" name="q" class="form-control"
                       value="{{ $search }}" placeholder="Nom, code ou description">
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
                        <th>Description</th>
                        <th>Validation requise</th>
                        <th>Documents</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($documentTypes as $documentType)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $documentType->name }}</div>
                        </td>
                        <td>
                            <code class="text-primary">{{ $documentType->code }}</code>
                        </td>
                        <td>
                            <small class="text-muted">{{ $documentType->description ? Str::limit($documentType->description, 50) : '-' }}</small>
                        </td>
                        <td>
                            @if($documentType->requires_validation)
                                <span class="badge bg-warning">Oui</span>
                            @else
                                <span class="badge bg-secondary">Non</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $documentType->documents_count }}</span>
                        </td>
                        <td>
                            @if($documentType->is_active)
                                <span class="badge bg-success">Actif</span>
                            @else
                                <span class="badge bg-danger">Inactif</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('document-types.show', $documentType) }}" class="btn btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @can('update', $documentType)
                                <a href="{{ route('document-types.edit', $documentType) }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endcan
                                @can('delete', $documentType)
                                <form method="POST" action="{{ route('document-types.destroy', $documentType) }}" 
                                      class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce type de document ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            Aucun type de document trouvé.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($documentTypes->hasPages())
        <div class="modern-card-footer">
            <div class="small text-muted">
                Affichage de {{ $documentTypes->firstItem() }} à {{ $documentTypes->lastItem() }} 
                sur {{ $documentTypes->total() }} type{{ $documentTypes->total() > 1 ? 's' : '' }} de document
            </div>
            <div class="pagination-modern">
                {{ $documentTypes->appends(request()->query())->links() }}
            </div>
        </div>
    @endif
</div>
@endsection

