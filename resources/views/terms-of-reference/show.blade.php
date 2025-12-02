@extends('layouts.app')

@section('title', 'Cahier des charges - ' . $meeting->title)

@section('content')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
        <li class="breadcrumb-item"><a href="{{ route('meetings.index') }}">Réunions</a></li>
        <li class="breadcrumb-item"><a href="{{ route('meetings.show', $meeting) }}">{{ Str::limit($meeting->title, 30) }}</a></li>
        <li class="breadcrumb-item active">Cahier des charges</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold">Cahier des charges</h3>
        <p class="text-muted mb-0 small">Réunion : {{ $meeting->title }}</p>
    </div>
    <div>
        <a href="{{ route('terms-of-reference.pdf', [$meeting, $termsOfReference]) }}" class="btn btn-outline-primary">
            <i class="bi bi-file-earmark-pdf me-1"></i> Exporter en PDF
        </a>
        @can('update', $meeting)
            <a href="{{ route('terms-of-reference.edit', [$meeting, $termsOfReference]) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil me-1"></i> Modifier
            </a>
        @endcan
        <a href="{{ route('meetings.show', $meeting) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Retour à la réunion
        </a>
    </div>
</div>

@include('partials.alerts')

<div class="row">
    <div class="col-md-8">
        {{-- Informations générales --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="bi bi-file-earmark-text text-primary"></i> Informations du cahier des charges
                </h5>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Pays hôte</dt>
                    <dd class="col-sm-8">
                        <strong>{{ $termsOfReference->host_country ?? 'Non renseigné' }}</strong>
                    </dd>
                    
                    <dt class="col-sm-4">Statut</dt>
                    <dd class="col-sm-8">
                        @php
                            $statusColors = [
                                'draft' => 'secondary',
                                'pending_validation' => 'warning',
                                'validated' => 'info',
                                'signed' => 'success',
                                'cancelled' => 'danger'
                            ];
                            $statusLabels = [
                                'draft' => 'Brouillon',
                                'pending_validation' => 'En attente de validation',
                                'validated' => 'Validé',
                                'signed' => 'Signé',
                                'cancelled' => 'Annulé'
                            ];
                        @endphp
                        <span class="badge bg-{{ $statusColors[$termsOfReference->status] ?? 'secondary' }}">
                            {{ $statusLabels[$termsOfReference->status] ?? $termsOfReference->status }}
                        </span>
                    </dd>
                    
                    <dt class="col-sm-4">Version</dt>
                    <dd class="col-sm-8">
                        Version {{ $termsOfReference->version }}
                        @if($termsOfReference->previousVersion)
                            <br><small class="text-muted">Version précédente : v{{ $termsOfReference->previousVersion->version }}</small>
                        @endif
                    </dd>
                    
                    <dt class="col-sm-4">Date de signature</dt>
                    <dd class="col-sm-8">
                        {{ $termsOfReference->signature_date ? $termsOfReference->signature_date->format('d/m/Y') : 'Non signé' }}
                    </dd>
                    
                    <dt class="col-sm-4">Période d'application</dt>
                    <dd class="col-sm-8">
                        @if($termsOfReference->effective_from || $termsOfReference->effective_until)
                            Du {{ $termsOfReference->effective_from ? $termsOfReference->effective_from->format('d/m/Y') : 'N/A' }}
                            au {{ $termsOfReference->effective_until ? $termsOfReference->effective_until->format('d/m/Y') : 'N/A' }}
                        @else
                            Non renseigné
                        @endif
                    </dd>
                    
                    @if($termsOfReference->validated_at)
                        <dt class="col-sm-4">Validé le</dt>
                        <dd class="col-sm-8">
                            {{ $termsOfReference->validated_at->format('d/m/Y à H:i') }}
                            @if($termsOfReference->validator)
                                par <strong>{{ $termsOfReference->validator->name }}</strong>
                            @endif
                        </dd>
                    @endif
                    
                    @if($termsOfReference->signed_at)
                        <dt class="col-sm-4">Signé le</dt>
                        <dd class="col-sm-8">
                            {{ $termsOfReference->signed_at->format('d/m/Y à H:i') }}
                            @if($termsOfReference->signerCeeac)
                                <br><small class="text-muted">Par la CEEAC : {{ $termsOfReference->signerCeeac->name }}</small>
                            @endif
                            @if($termsOfReference->signed_by_host_name)
                                <br><small class="text-muted">Par le pays hôte : {{ $termsOfReference->signed_by_host_name }} 
                                @if($termsOfReference->signed_by_host_position)
                                    ({{ $termsOfReference->signed_by_host_position }})
                                @endif
                                </small>
                            @endif
                        </dd>
                    @endif
                    
                    @if($termsOfReference->signed_document_path)
                        <dt class="col-sm-4">Document signé</dt>
                        <dd class="col-sm-8">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-file-earmark-pdf text-danger"></i>
                                <span>{{ $termsOfReference->signed_document_original_name }}</span>
                                @if($termsOfReference->signed_document_size)
                                    <span class="text-muted">({{ number_format($termsOfReference->signed_document_size / 1024, 2) }} KB)</span>
                                @endif
                                <a href="{{ route('terms-of-reference.download-signed', [$meeting, $termsOfReference]) }}" 
                                   class="btn btn-sm btn-outline-primary ms-2">
                                    <i class="bi bi-download"></i> Télécharger
                                </a>
                            </div>
                            @if($termsOfReference->signedDocumentUploader)
                                <small class="text-muted">
                                    Uploadé par {{ $termsOfReference->signedDocumentUploader->name }} 
                                    le {{ $termsOfReference->signed_document_uploaded_at->format('d/m/Y à H:i') }}
                                </small>
                            @endif
                        </dd>
                    @endif
                </dl>
            </div>
        </div>

        {{-- Responsabilités CEEAC --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-building"></i> Responsabilités de la CEEAC
                </h5>
            </div>
            <div class="card-body">
                @if($termsOfReference->responsibilities_ceeac)
                    <div class="text-content">
                        {!! nl2br(e($termsOfReference->responsibilities_ceeac)) !!}
                    </div>
                @else
                    <p class="text-muted mb-0">Non renseigné</p>
                @endif
            </div>
        </div>

        {{-- Responsabilités pays hôte --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="bi bi-geo-alt"></i> Responsabilités du pays hôte
                </h5>
            </div>
            <div class="card-body">
                @if($termsOfReference->responsibilities_host)
                    <div class="text-content">
                        {!! nl2br(e($termsOfReference->responsibilities_host)) !!}
                    </div>
                @else
                    <p class="text-muted mb-0">Non renseigné</p>
                @endif
            </div>
        </div>

        {{-- Partage financier --}}
        @if($termsOfReference->financial_sharing)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-cash-coin"></i> Partage des charges financières
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-content">
                        {!! nl2br(e($termsOfReference->financial_sharing)) !!}
                    </div>
                </div>
            </div>
        @endif

        {{-- Partage logistique --}}
        @if($termsOfReference->logistical_sharing)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="bi bi-truck"></i> Partage des charges logistiques
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-content">
                        {!! nl2br(e($termsOfReference->logistical_sharing)) !!}
                    </div>
                </div>
            </div>
        @endif

        {{-- Obligations --}}
        @if($termsOfReference->obligations_ceeac || $termsOfReference->obligations_host)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-list-check"></i> Obligations respectives
                    </h5>
                </div>
                <div class="card-body">
                    @if($termsOfReference->obligations_ceeac)
                        <h6 class="text-primary">Obligations de la CEEAC</h6>
                        <div class="text-content mb-3">
                            {!! nl2br(e($termsOfReference->obligations_ceeac)) !!}
                        </div>
                    @endif
                    
                    @if($termsOfReference->obligations_host)
                        <h6 class="text-success">Obligations du pays hôte</h6>
                        <div class="text-content">
                            {!! nl2br(e($termsOfReference->obligations_host)) !!}
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Termes additionnels --}}
        @if($termsOfReference->additional_terms)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-file-text"></i> Termes additionnels
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-content">
                        {!! nl2br(e($termsOfReference->additional_terms)) !!}
                    </div>
                </div>
            </div>
        @endif

        {{-- Notes --}}
        @if($termsOfReference->notes)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-sticky"></i> Notes
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-content">
                        {!! nl2br(e($termsOfReference->notes)) !!}
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Colonne latérale : Actions et historique --}}
    <div class="col-md-4">
        {{-- Actions --}}
        @can('update', $meeting)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="bi bi-gear"></i> Actions
                    </h6>
                </div>
                <div class="card-body">
                    @if($termsOfReference->status === 'draft' || $termsOfReference->status === 'pending_validation')
                        <form action="{{ route('terms-of-reference.validate', [$meeting, $termsOfReference]) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-check-circle"></i> Valider le cahier des charges
                            </button>
                        </form>
                    @endif

                    @if($termsOfReference->status === 'validated')
                        <button type="button" class="btn btn-primary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#signModal">
                            <i class="bi bi-pen"></i> Signer le cahier des charges
                        </button>
                    @endif

                    @if($termsOfReference->isSigned())
                        <form action="{{ route('terms-of-reference.create-version', [$meeting, $termsOfReference]) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary w-100">
                                <i class="bi bi-file-earmark-plus"></i> Créer une nouvelle version
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('terms-of-reference.edit', [$meeting, $termsOfReference]) }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-pencil"></i> Modifier
                    </a>
                </div>
            </div>
        @endcan

        {{-- Historique des versions --}}
        @if($termsOfReference->nextVersions->count() > 0 || $termsOfReference->previousVersion)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="bi bi-clock-history"></i> Historique des versions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @if($termsOfReference->previousVersion)
                            <a href="{{ route('terms-of-reference.show', [$meeting, $termsOfReference->previousVersion]) }}" 
                               class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between">
                                    <span>Version {{ $termsOfReference->previousVersion->version }}</span>
                                    <small class="text-muted">{{ $termsOfReference->previousVersion->created_at->format('d/m/Y') }}</small>
                                </div>
                            </a>
                        @endif
                        
                        <div class="list-group-item bg-light">
                            <div class="d-flex justify-content-between">
                                <strong>Version {{ $termsOfReference->version }} (actuelle)</strong>
                                <small class="text-muted">{{ $termsOfReference->created_at->format('d/m/Y') }}</small>
                            </div>
                        </div>

                        @foreach($termsOfReference->nextVersions as $nextVersion)
                            <a href="{{ route('terms-of-reference.show', [$meeting, $nextVersion]) }}" 
                               class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between">
                                    <span>Version {{ $nextVersion->version }}</span>
                                    <small class="text-muted">{{ $nextVersion->created_at->format('d/m/Y') }}</small>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- Informations sur la réunion --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0">
                    <i class="bi bi-calendar-event"></i> Réunion associée
                </h6>
            </div>
            <div class="card-body">
                <h6 class="mb-2">{{ $meeting->title }}</h6>
                @if($meeting->start_at)
                    <p class="mb-1">
                        <i class="bi bi-calendar"></i> 
                        {{ $meeting->start_at->format('d/m/Y') }}
                    </p>
                    <p class="mb-0">
                        <i class="bi bi-clock"></i> 
                        {{ $meeting->start_at->format('H:i') }}
                    </p>
                @endif
                <a href="{{ route('meetings.show', $meeting) }}" class="btn btn-sm btn-outline-primary mt-2">
                    <i class="bi bi-eye"></i> Voir la réunion
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Modal pour la signature --}}
@if($termsOfReference->status === 'validated')
<div class="modal fade" id="signModal" tabindex="-1" aria-labelledby="signModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('terms-of-reference.sign', [$meeting, $termsOfReference]) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="signModalLabel">Signer le cahier des charges</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nom du signataire (pays hôte) <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="signed_by_host_name" 
                               class="form-control" 
                               value="{{ old('signed_by_host_name') }}"
                               required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fonction du signataire (pays hôte) <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="signed_by_host_position" 
                               class="form-control" 
                               value="{{ old('signed_by_host_position') }}"
                               required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date de signature</label>
                        <input type="date" 
                               name="signature_date" 
                               class="form-control" 
                               value="{{ old('signature_date', now()->format('Y-m-d')) }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Signer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<style>
.text-content {
    white-space: pre-wrap;
    word-wrap: break-word;
}
</style>
@endsection

