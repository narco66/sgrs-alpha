@extends('layouts.app')

@section('title', 'Modifier le cahier des charges')

@section('content')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
        <li class="breadcrumb-item"><a href="{{ route('meetings.index') }}">Réunions</a></li>
        <li class="breadcrumb-item"><a href="{{ route('meetings.show', $meeting) }}">{{ Str::limit($meeting->title, 30) }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('terms-of-reference.show', [$meeting, $termsOfReference]) }}">Cahier des charges</a></li>
        <li class="breadcrumb-item active">Modifier</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold">Modifier le cahier des charges</h3>
        <p class="text-muted mb-0 small">Réunion : {{ $meeting->title }} - Version {{ $termsOfReference->version }}</p>
    </div>
    <a href="{{ route('terms-of-reference.show', [$meeting, $termsOfReference]) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

@include('partials.alerts')

@if($termsOfReference->isSigned())
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle"></i> 
        <strong>Attention :</strong> Ce cahier des charges est déjà signé. Les modifications créeront automatiquement une nouvelle version.
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('terms-of-reference.update', [$meeting, $termsOfReference]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Pays hôte <span class="text-danger">*</span></label>
                    <input type="text" 
                           name="host_country" 
                           class="form-control @error('host_country') is-invalid @enderror" 
                           value="{{ old('host_country', $termsOfReference->host_country) }}"
                           placeholder="Ex: République du Congo"
                           required>
                    @error('host_country')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Date de signature prévue</label>
                    <input type="date" 
                           name="signature_date" 
                           class="form-control @error('signature_date') is-invalid @enderror" 
                           value="{{ old('signature_date', $termsOfReference->signature_date?->format('Y-m-d')) }}">
                    @error('signature_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Date d'entrée en vigueur</label>
                    <input type="date" 
                           name="effective_from" 
                           class="form-control @error('effective_from') is-invalid @enderror" 
                           value="{{ old('effective_from', $termsOfReference->effective_from?->format('Y-m-d')) }}">
                    @error('effective_from')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Date de fin de validité</label>
                    <input type="date" 
                           name="effective_until" 
                           class="form-control @error('effective_until') is-invalid @enderror" 
                           value="{{ old('effective_until', $termsOfReference->effective_until?->format('Y-m-d')) }}">
                    @error('effective_until')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Responsabilités de la CEEAC <span class="text-danger">*</span></label>
                    <textarea name="responsibilities_ceeac" 
                              class="form-control @error('responsibilities_ceeac') is-invalid @enderror" 
                              rows="6"
                              required>{{ old('responsibilities_ceeac', $termsOfReference->responsibilities_ceeac) }}</textarea>
                    @error('responsibilities_ceeac')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Responsabilités du pays hôte <span class="text-danger">*</span></label>
                    <textarea name="responsibilities_host" 
                              class="form-control @error('responsibilities_host') is-invalid @enderror" 
                              rows="6"
                              required>{{ old('responsibilities_host', $termsOfReference->responsibilities_host) }}</textarea>
                    @error('responsibilities_host')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Partage des charges financières</label>
                    <textarea name="financial_sharing" 
                              class="form-control @error('financial_sharing') is-invalid @enderror" 
                              rows="4">{{ old('financial_sharing', $termsOfReference->financial_sharing) }}</textarea>
                    @error('financial_sharing')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Partage des charges logistiques</label>
                    <textarea name="logistical_sharing" 
                              class="form-control @error('logistical_sharing') is-invalid @enderror" 
                              rows="4">{{ old('logistical_sharing', $termsOfReference->logistical_sharing) }}</textarea>
                    @error('logistical_sharing')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Obligations de la CEEAC</label>
                    <textarea name="obligations_ceeac" 
                              class="form-control @error('obligations_ceeac') is-invalid @enderror" 
                              rows="4">{{ old('obligations_ceeac', $termsOfReference->obligations_ceeac) }}</textarea>
                    @error('obligations_ceeac')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Obligations du pays hôte</label>
                    <textarea name="obligations_host" 
                              class="form-control @error('obligations_host') is-invalid @enderror" 
                              rows="4">{{ old('obligations_host', $termsOfReference->obligations_host) }}</textarea>
                    @error('obligations_host')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Termes additionnels</label>
                    <textarea name="additional_terms" 
                              class="form-control @error('additional_terms') is-invalid @enderror" 
                              rows="4">{{ old('additional_terms', $termsOfReference->additional_terms) }}</textarea>
                    @error('additional_terms')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" 
                              class="form-control @error('notes') is-invalid @enderror" 
                              rows="3">{{ old('notes', $termsOfReference->notes) }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">
                        <i class="bi bi-file-earmark-pdf"></i> Document physique signé
                    </label>
                    
                    @if($termsOfReference->signed_document_path)
                        <div class="alert alert-info mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-file-earmark-check"></i> 
                                    <strong>Document actuel :</strong> 
                                    {{ $termsOfReference->signed_document_original_name }}
                                    @if($termsOfReference->signed_document_size)
                                        <span class="text-muted">
                                            ({{ number_format($termsOfReference->signed_document_size / 1024, 2) }} KB)
                                        </span>
                                    @endif
                                    @if($termsOfReference->signedDocumentUploader)
                                        <br><small class="text-muted">
                                            Uploadé par {{ $termsOfReference->signedDocumentUploader->name }} 
                                            le {{ $termsOfReference->signed_document_uploaded_at->format('d/m/Y H:i') }}
                                        </small>
                                    @endif
                                </div>
                                <div>
                                    <a href="{{ route('terms-of-reference.download-signed', [$meeting, $termsOfReference]) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-download"></i> Télécharger
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="remove_signed_document" 
                                   id="remove_signed_document" 
                                   value="1">
                            <label class="form-check-label" for="remove_signed_document">
                                Supprimer le document actuel
                            </label>
                        </div>
                    @endif
                    
                    <input type="file" 
                           name="signed_document" 
                           class="form-control @error('signed_document') is-invalid @enderror"
                           accept=".pdf,.jpg,.jpeg,.png">
                    @error('signed_document')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">
                        @if($termsOfReference->signed_document_path)
                            Vous pouvez remplacer le document actuel en uploadant un nouveau fichier.
                        @else
                            Vous pouvez joindre le document physique signé entre les deux parties (PDF ou image scannée).
                        @endif
                        Formats acceptés : PDF, JPG, JPEG, PNG. Taille maximale : 10 MB.
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('terms-of-reference.show', [$meeting, $termsOfReference]) }}" class="btn btn-outline-secondary">
                    Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

