@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Nouveau type de document</h4>
        <p class="text-muted mb-0">
            Créez un nouveau type de document pour le système SGRS-CEEAC.
        </p>
    </div>
    <a href="{{ route('document-types.index') }}" class="btn btn-outline-secondary">
        Retour à la liste
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        @include('partials.alerts')

        <form method="POST" action="{{ route('document-types.store') }}">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nom <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Code <span class="text-danger">*</span></label>
                    <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                           value="{{ old('code') }}" required style="text-transform: uppercase;">
                    <div class="form-text">Code unique (ex: ODJ, PV, RAPPORT)</div>
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3 mt-3">
                <label class="form-label">Description</label>
                <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="requires_validation" 
                               id="requires_validation" value="1" @checked(old('requires_validation'))>
                        <label class="form-check-label" for="requires_validation">
                            Validation requise
                        </label>
                    </div>
                    <div class="form-text">Si activé, les documents de ce type nécessiteront une validation (Protocole, SG, Président)</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Ordre d'affichage</label>
                    <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror"
                           value="{{ old('sort_order', 0) }}" min="0">
                    @error('sort_order')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3 mt-3">
                <label class="form-label">Statut</label>
                <select name="is_active" class="form-select @error('is_active') is-invalid @enderror">
                    <option value="1" @selected(old('is_active', true))>Actif</option>
                    <option value="0" @selected(old('is_active') === '0')>Inactif</option>
                </select>
                @error('is_active')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('document-types.index') }}" class="btn btn-outline-secondary">
                    Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-1"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

