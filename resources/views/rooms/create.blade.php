@extends('layouts.app')

@section('title', 'Nouvelle salle de réunion')

@section('content')
{{-- Fil d’Ariane --}}
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">Accueil</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('rooms.index') }}">Salles de réunion</a>
        </li>
        <li class="breadcrumb-item active">Nouvelle salle</li>
    </ol>
</nav>

{{-- En-tête --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="page-title mb-1">Nouvelle salle de réunion</h3>
        <p class="text-muted mb-0 small">Ajoutez une salle conforme au modèle institutionnel CEEAC.</p>
    </div>
    <a href="{{ route('rooms.index') }}" class="btn btn-modern btn-modern-secondary">
        <i class="bi bi-arrow-left"></i>
        Retour à la liste
    </a>
</div>

{{-- Erreurs de validation --}}
@if ($errors->any())
    <x-modern-alert type="danger" dismissible>
        <strong><i class="bi bi-exclamation-triangle me-2"></i>Erreurs détectées :</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </x-modern-alert>
@endif

<form method="POST" action="{{ route('rooms.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="row g-4">
        {{-- Colonne principale --}}
        <div class="col-lg-8">
            {{-- Informations générales --}}
            <div class="modern-card mb-4">
                <div class="modern-card-header">
                    <h5 class="modern-card-title">
                        <i class="bi bi-door-open text-primary me-2"></i>
                        Informations de la salle
                    </h5>
                </div>
                <div class="modern-card-body">
                    <div class="row g-3">
                        {{-- Nom --}}
                        <div class="col-md-8">
                            <label class="form-label">
                                Nom de la salle
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="Ex : Salle de Conférence du 5ème étage"
                                   value="{{ old('name') }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Code --}}
                        <div class="col-md-4">
                            <label class="form-label">
                                Code
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="code"
                                   class="form-control @error('code') is-invalid @enderror"
                                   placeholder="Ex : SC-05"
                                   value="{{ old('code') }}"
                                   required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Code unique pour identifier la salle (sera enregistré en majuscules).
                            </div>
                        </div>

                        {{-- Localisation --}}
                        <div class="col-md-8">
                            <label class="form-label">Localisation</label>
                            <input type="text"
                                   name="location"
                                   class="form-control @error('location') is-invalid @enderror"
                                   placeholder="Ex : Bâtiment principal, 5ème étage"
                                   value="{{ old('location') }}">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Capacité --}}
                        <div class="col-md-4">
                            <label class="form-label">
                                Capacité
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number"
                                       name="capacity"
                                       class="form-control @error('capacity') is-invalid @enderror"
                                       placeholder="50"
                                       min="1"
                                       max="1000"
                                       value="{{ old('capacity') }}"
                                       required>
                                <span class="input-group-text">personnes</span>
                            </div>
                            @error('capacity')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description"
                                      class="form-control @error('description') is-invalid @enderror"
                                      rows="3"
                                      placeholder="Description de la salle, caractéristiques particulières...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Équipements --}}
            <div class="modern-card">
                <div class="modern-card-header">
                    <h5 class="modern-card-title">
                        <i class="bi bi-tools text-primary me-2"></i>
                        Équipements disponibles
                    </h5>
                </div>
                <div class="modern-card-body">
                    <p class="text-muted small mb-3">
                        Sélectionnez les équipements présents dans la salle. Ils seront réutilisés dans les documents logistiques.
                    </p>
                    <div class="row g-3">
                        @php
                            $selectedEquipments = old('equipments', []);
                        @endphp
                        @foreach($equipmentOptions as $value => $label)
                            <div class="col-md-4 col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="equipments[]"
                                           value="{{ $value }}"
                                           id="equip_{{ $value }}"
                                           @checked(in_array($value, $selectedEquipments))>
                                    <label class="form-check-label" for="equip_{{ $value }}">
                                        {{ $label }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Colonne latérale --}}
        <div class="col-lg-4">
            {{-- Image --}}
            <div class="modern-card mb-4">
                <div class="modern-card-header">
                    <h5 class="modern-card-title">
                        <i class="bi bi-image text-primary me-2"></i>
                        Photo de la salle
                    </h5>
                </div>
                <div class="modern-card-body">
                    <div class="mb-3">
                        {{-- Zone de prévisualisation de l’image --}}
                        <div id="imagePreview"
                             class="border rounded mb-3 d-flex align-items-center justify-content-center bg-light"
                             style="height: 190px; overflow: hidden;">
                            <div class="text-center text-muted">
                                <i class="bi bi-image fs-1"></i>
                                <p class="small mb-0">Aucune image sélectionnée</p>
                            </div>
                        </div>

                        <input type="file"
                               name="image"
                               class="form-control @error('image') is-invalid @enderror"
                               accept="image/jpeg,image/png,image/jpg,image/webp"
                               id="imageInput">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror>
                        <div class="form-text">
                            Formats acceptés : JPEG, PNG, WebP &mdash; taille maximale 5 Mo.
                        </div>
                    </div>
                </div>
            </div>

            {{-- Statut --}}
            <div class="modern-card mb-4">
                <div class="modern-card-header">
                    <h5 class="modern-card-title">
                        <i class="bi bi-toggle-on text-primary me-2"></i>
                        Statut de la salle
                    </h5>
                </div>
                <div class="modern-card-body">
                    <div class="form-check form-switch">
                        <input class="form-check-input"
                               type="checkbox"
                               name="is_active"
                               id="is_active"
                               value="1"
                               @checked(old('is_active', true))>
                        <label class="form-check-label ms-2" for="is_active">
                            <strong>Salle active</strong>
                            <br>
                            <small class="text-muted">
                                Une salle active peut être réservée pour des réunions et apparaît dans les listes de sélection.
                            </small>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="modern-card">
                <div class="modern-card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-modern btn-modern-primary">
                            <i class="bi bi-check-circle me-1"></i>
                            Créer la salle
                        </button>
                        <a href="{{ route('rooms.index') }}" class="btn btn-modern btn-modern-secondary">
                            <i class="bi bi-x-circle me-1"></i>
                            Annuler
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');

    if (!imageInput || !imagePreview) {
        return;
    }

    imageInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) {
            imagePreview.innerHTML = `
                <div class="text-center text-muted">
                    <i class="bi bi-image fs-1"></i>
                    <p class="small mb-0">Aucune image sélectionnée</p>
                </div>
            `;
            return;
        }

        const reader = new FileReader();
        reader.onload = function (ev) {
            imagePreview.innerHTML = `
                <img src="${ev.target.result}"
                     alt="Prévisualisation de la salle"
                     class="img-fluid rounded"
                     style="max-height: 190px; width: 100%; object-fit: cover;">
            `;
        };
        reader.readAsDataURL(file);
    });
});
</script>
@endpush
@endsection
