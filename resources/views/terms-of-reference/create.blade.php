@extends('layouts.app')

@section('title', 'Créer un cahier des charges')

@section('content')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
        <li class="breadcrumb-item"><a href="{{ route('meetings.index') }}">Réunions</a></li>
        <li class="breadcrumb-item"><a href="{{ route('meetings.show', $meeting) }}">{{ Str::limit($meeting->title, 30) }}</a></li>
        <li class="breadcrumb-item active">Créer un cahier des charges</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold">Créer un cahier des charges</h3>
        <p class="text-muted mb-0 small">Réunion : {{ $meeting->title }}</p>
    </div>
    <a href="{{ route('meetings.show', $meeting) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

@include('partials.alerts')

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('terms-of-reference.store', $meeting) }}" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Pays hôte <span class="text-danger">*</span></label>
                    <input type="text" 
                           name="host_country" 
                           class="form-control @error('host_country') is-invalid @enderror" 
                           value="{{ old('host_country') }}"
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
                           value="{{ old('signature_date') }}">
                    @error('signature_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Date d'entrée en vigueur</label>
                    <input type="date" 
                           name="effective_from" 
                           class="form-control @error('effective_from') is-invalid @enderror" 
                           value="{{ old('effective_from') }}">
                    @error('effective_from')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Date de fin de validité</label>
                    <input type="date" 
                           name="effective_until" 
                           class="form-control @error('effective_until') is-invalid @enderror" 
                           value="{{ old('effective_until') }}">
                    @error('effective_until')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Responsabilités de la CEEAC <span class="text-danger">*</span></label>
                    <textarea name="responsibilities_ceeac" 
                              class="form-control @error('responsibilities_ceeac') is-invalid @enderror" 
                              rows="6"
                              required>{{ old('responsibilities_ceeac') }}</textarea>
                    @error('responsibilities_ceeac')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Responsabilités du pays hôte <span class="text-danger">*</span></label>
                    <textarea name="responsibilities_host" 
                              class="form-control @error('responsibilities_host') is-invalid @enderror" 
                              rows="6"
                              required>{{ old('responsibilities_host') }}</textarea>
                    @error('responsibilities_host')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Partage des charges financières</label>
                    <textarea name="financial_sharing" 
                              class="form-control @error('financial_sharing') is-invalid @enderror" 
                              rows="4">{{ old('financial_sharing') }}</textarea>
                    @error('financial_sharing')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Partage des charges logistiques</label>
                    <textarea name="logistical_sharing" 
                              class="form-control @error('logistical_sharing') is-invalid @enderror" 
                              rows="4">{{ old('logistical_sharing') }}</textarea>
                    @error('logistical_sharing')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Obligations de la CEEAC</label>
                    <textarea name="obligations_ceeac" 
                              class="form-control @error('obligations_ceeac') is-invalid @enderror" 
                              rows="4">{{ old('obligations_ceeac') }}</textarea>
                    @error('obligations_ceeac')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Obligations du pays hôte</label>
                    <textarea name="obligations_host" 
                              class="form-control @error('obligations_host') is-invalid @enderror" 
                              rows="4">{{ old('obligations_host') }}</textarea>
                    @error('obligations_host')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Termes additionnels</label>
                    <textarea name="additional_terms" 
                              class="form-control @error('additional_terms') is-invalid @enderror" 
                              rows="4">{{ old('additional_terms') }}</textarea>
                    @error('additional_terms')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" 
                              class="form-control @error('notes') is-invalid @enderror" 
                              rows="3">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">
                        <i class="bi bi-file-earmark-pdf"></i> Document physique signé (optionnel)
                    </label>
                    <input type="file" 
                           name="signed_document" 
                           class="form-control @error('signed_document') is-invalid @enderror"
                           accept=".pdf,.jpg,.jpeg,.png">
                    @error('signed_document')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">
                        Vous pouvez joindre le document physique signé entre les deux parties (PDF ou image scannée). 
                        Formats acceptés : PDF, JPG, JPEG, PNG. Taille maximale : 10 MB.
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('meetings.show', $meeting) }}" class="btn btn-outline-secondary">
                    Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Créer le cahier des charges
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

