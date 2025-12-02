@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">{{ $documentType->name }}</h4>
        <p class="text-muted mb-0">
            Détails du type de document
        </p>
    </div>
    <div class="btn-group">
        @can('update', $documentType)
        <a href="{{ route('document-types.edit', $documentType) }}" class="btn btn-outline-primary">
            <i class="bi bi-pencil me-1"></i> Modifier
        </a>
        @endcan
        <a href="{{ route('document-types.index') }}" class="btn btn-outline-secondary">
            Retour à la liste
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-white">
                <h6 class="mb-0">Informations</h6>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Nom</dt>
                    <dd class="col-sm-8">{{ $documentType->name }}</dd>

                    <dt class="col-sm-4">Code</dt>
                    <dd class="col-sm-8"><code class="text-primary">{{ $documentType->code }}</code></dd>

                    <dt class="col-sm-4">Description</dt>
                    <dd class="col-sm-8">{{ $documentType->description ?? '-' }}</dd>

                    <dt class="col-sm-4">Validation requise</dt>
                    <dd class="col-sm-8">
                        @if($documentType->requires_validation)
                            <span class="badge bg-warning">Oui</span>
                            <small class="text-muted ms-2">(Protocole → SG → Président)</small>
                        @else
                            <span class="badge bg-secondary">Non</span>
                        @endif
                    </dd>

                    <dt class="col-sm-4">Ordre d'affichage</dt>
                    <dd class="col-sm-8">{{ $documentType->sort_order }}</dd>

                    <dt class="col-sm-4">Statut</dt>
                    <dd class="col-sm-8">
                        @if($documentType->is_active)
                            <span class="badge bg-success">Actif</span>
                        @else
                            <span class="badge bg-danger">Inactif</span>
                        @endif
                    </dd>

                    <dt class="col-sm-4">Date de création</dt>
                    <dd class="col-sm-8">{{ $documentType->created_at->format('d/m/Y H:i') }}</dd>
                </dl>
            </div>
        </div>

        @if($documentType->documents->count() > 0)
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h6 class="mb-0">Documents de ce type ({{ $documentType->documents->count() }})</h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @foreach($documentType->documents->take(10) as $document)
                        <a href="{{ route('documents.show', $document) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-1">{{ $document->title }}</h6>
                                    <small class="text-muted">{{ $document->created_at->format('d/m/Y') }}</small>
                                </div>
                                <span class="badge bg-{{ $document->validation_status === 'approved' ? 'success' : 'warning' }}">
                                    {{ $document->validation_status }}
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
                @if($documentType->documents->count() > 10)
                    <div class="mt-3 text-center">
                        <a href="{{ route('documents.index', ['type' => $documentType->id]) }}" class="btn btn-sm btn-outline-primary">
                            Voir tous les documents
                        </a>
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

