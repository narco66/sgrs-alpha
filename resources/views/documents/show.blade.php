@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">{{ $document->title }}</h4>
        <p class="text-muted mb-0">
            Détails du document
        </p>
    </div>
    <div class="btn-group">
        <a href="{{ route('documents.download', $document) }}" class="btn btn-outline-primary">
            <i class="bi bi-download me-1"></i> Télécharger
        </a>
        @can('update', $document)
        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#uploadVersionModal">
            <i class="bi bi-upload me-1"></i> Nouvelle version
        </button>
        @endcan
        <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary">
            Retour à la liste
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-white">
                <h6 class="mb-0">Informations du document</h6>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Titre</dt>
                    <dd class="col-sm-8">{{ $document->title }}</dd>

                    <dt class="col-sm-4">Description</dt>
                    <dd class="col-sm-8">{{ $document->description ?? '-' }}</dd>

                    <dt class="col-sm-4">Type de document</dt>
                    <dd class="col-sm-8">
                        @if($document->type)
                            <span class="badge bg-primary">{{ $document->type->name }}</span>
                        @else
                            <span class="badge bg-secondary">{{ $document->type_label }}</span>
                        @endif
                    </dd>

                    <dt class="col-sm-4">Réunion associée</dt>
                    <dd class="col-sm-8">
                        @if($document->meeting)
                            <a href="{{ route('meetings.show', $document->meeting) }}">
                                {{ $document->meeting->title }}
                            </a>
                        @else
                            <span class="text-muted">Aucune</span>
                        @endif
                    </dd>

                    <dt class="col-sm-4">Auteur</dt>
                    <dd class="col-sm-8">{{ $document->uploader->name }}</dd>

                    <dt class="col-sm-4">Fichier</dt>
                    <dd class="col-sm-8">
                        <i class="{{ $document->icon_class }} me-2"></i>
                        {{ $document->original_name }}
                        <small class="text-muted">({{ number_format($document->file_size / 1024, 2) }} KB)</small>
                    </dd>

                    <dt class="col-sm-4">Statut de validation</dt>
                    <dd class="col-sm-8">
                        @php
                            $statusColors = [
                                'draft' => 'secondary',
                                'pending' => 'warning',
                                'approved' => 'success',
                                'rejected' => 'danger',
                                'archived' => 'info',
                            ];
                            $color = $statusColors[$document->validation_status] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $color }}">{{ ucfirst($document->validation_status) }}</span>
                    </dd>

                    <dt class="col-sm-4">Date de création</dt>
                    <dd class="col-sm-8">{{ $document->created_at->format('d/m/Y H:i') }}</dd>
                </dl>
            </div>
        </div>

        @if($document->validations->count() > 0)
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-white">
                <h6 class="mb-0">Workflow de validation</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach(['protocole', 'sg', 'president'] as $level)
                        @php
                            $validation = $document->validations->firstWhere('validation_level', $level);
                        @endphp
                        <div class="d-flex align-items-start mb-3">
                            <div class="flex-shrink-0">
                                @if($validation && $validation->status === 'approved')
                                    <i class="bi bi-check-circle-fill text-success fs-4"></i>
                                @elseif($validation && $validation->status === 'rejected')
                                    <i class="bi bi-x-circle-fill text-danger fs-4"></i>
                                @else
                                    <i class="bi bi-circle text-muted fs-4"></i>
                                @endif
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">
                                    {{ match($level) {
                                        'protocole' => 'Protocole',
                                        'sg' => 'Secrétariat Général',
                                        'president' => 'Président',
                                        default => $level
                                    } }}
                                </h6>
                                @if($validation)
                                    <p class="mb-1">
                                        <span class="badge bg-{{ $validation->status === 'approved' ? 'success' : ($validation->status === 'rejected' ? 'danger' : 'warning') }}">
                                            {{ $validation->status_label }}
                                        </span>
                                        @if($validation->validated_by)
                                            par {{ $validation->validator->name }}
                                        @endif
                                    </p>
                                    @if($validation->comments)
                                        <p class="text-muted small mb-0">{{ $validation->comments }}</p>
                                    @endif
                                    @if($validation->validated_at)
                                        <small class="text-muted">{{ $validation->validated_at->format('d/m/Y H:i') }}</small>
                                    @endif
                                @else
                                    <p class="text-muted mb-0">En attente</p>
                                @endif
                            </div>
                            @can('update', $document)
                            @if($validation && $validation->status === 'pending')
                            <div class="flex-shrink-0">
                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#validateModal"
                                        data-level="{{ $level }}"
                                        data-validation-id="{{ $validation->id }}">
                                    Valider
                                </button>
                            </div>
                            @endif
                            @endcan
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        @if($document->versions->count() > 1)
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h6 class="mb-0">Historique des versions ({{ $document->versions->count() }})</h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @foreach($document->versions->sortByDesc('version_number') as $version)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">Version {{ $version->version_number }}</h6>
                                    <p class="mb-1 text-muted small">
                                        {{ $version->original_name }}
                                        <span class="ms-2">({{ number_format($version->file_size / 1024, 2) }} KB)</span>
                                    </p>
                                    @if($version->change_summary)
                                        <p class="mb-0 small">{{ $version->change_summary }}</p>
                                    @endif
                                    <small class="text-muted">
                                        Par {{ $version->creator->name }} le {{ $version->created_at->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                                @if($version->version_number === $document->versions->max('version_number'))
                                    <span class="badge bg-primary">Version actuelle</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal pour uploader une nouvelle version -->
@can('update', $document)
<div class="modal fade" id="uploadVersionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('documents.upload-version', $document) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Uploader une nouvelle version</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Fichier <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control" required accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Résumé des changements</label>
                        <textarea name="change_summary" rows="3" class="form-control" 
                                  placeholder="Décrivez les modifications apportées à cette version..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Uploader</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal pour valider un document -->
<div class="modal fade" id="validateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('documents.validate', $document) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Valider le document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="validation_level" id="validation_level">
                    <div class="mb-3">
                        <label class="form-label">Statut <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="approved">Approuvé</option>
                            <option value="rejected">Rejeté</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Commentaires</label>
                        <textarea name="comments" rows="3" class="form-control" 
                                  placeholder="Ajoutez des commentaires si nécessaire..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const validateModal = document.getElementById('validateModal');
    if (validateModal) {
        validateModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const level = button.getAttribute('data-level');
            document.getElementById('validation_level').value = level;
        });
    }
});
</script>
@endcan
@endsection

