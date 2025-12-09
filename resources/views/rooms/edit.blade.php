@extends('layouts.app')

@section('title', 'Modifier la salle')

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
        <li class="breadcrumb-item">
            <a href="{{ route('rooms.show', $room) }}">{{ $room->name }}</a>
        </li>
        <li class="breadcrumb-item active">Modifier</li>
    </ol>
</nav>

{{-- En-tête --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="page-title mb-1">Modifier la salle</h3>
        <p class="text-muted mb-0 small">
            Mettez à jour les informations de la salle tout en conservant son historique d’utilisation.
        </p>
    </div>
    <a href="{{ route('rooms.show', $room) }}" class="btn btn-modern btn-modern-secondary">
        <i class="bi bi-arrow-left"></i>
        Retour à la fiche
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

<form method="POST" action="{{ route('rooms.update', $room) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

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
                                   value="{{ old('name', $room->name) }}"
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
                                   value="{{ old('code', $room->code) }}"
                                   required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Code unique pour identifier la salle (enregistré en majuscules).
                            </div>
                        </div>

                        {{-- Localisation --}}
                        <div class="col-md-8">
                            <label class="form-label">Localisation</label>
                            <input type="text"
                                   name="location"
                                   class="form-control @error('location') is-invalid @enderror"
                                   placeholder="Ex : Bâtiment principal, 5ème étage"
                                   value="{{ old('location', $room->location) }}">
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
                                       value="{{ old('capacity', $room->capacity) }}"
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
                                      placeholder="Description de la salle, caractéristiques particulières...">{{ old('description', $room->description) }}</textarea>
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
                        Mettez à jour les équipements disponibles dans cette salle. Ils sont utilisés dans la logistique des réunions.
                    </p>
                    <div class="row g-3">
                        @php
                            $selectedEquipments = old('equipments', $room->equipments ?? []);
                        @endphp
                        @foreach($equipmentOptions as $value => $label)
                            <div class="col-md-4 col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="equipments[]"
                                           value="{{ $value }}"
                                           id="equip_{{ $value }}"
                                           @checked(is_array($selectedEquipments) && in_array($value, $selectedEquipments))>
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
                        <div id="imagePreview"
                             class="border rounded mb-3 d-flex align-items-center justify-content-center bg-light"
                             style="height: 190px; overflow: hidden;">
                            @if($room->image)
                                <img src="{{ $room->image_url }}"
                                     alt="{{ $room->name }}"
                                     class="img-fluid rounded"
                                     style="max-height: 190px; width: 100%; object-fit: cover;"
                                     id="currentImage">
                            @else
                                <div class="text-center text-muted">
                                    <i class="bi bi-image fs-1"></i>
                                    <p class="small mb-0">Aucune image enregistrée</p>
                                </div>
                            @endif
                        </div>

                        @if($room->image)
                            <div class="form-check mb-3">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="remove_image"
                                       value="1"
                                       id="removeImage">
                                <label class="form-check-label text-danger" for="removeImage">
                                    <i class="bi bi-trash me-1"></i>
                                    Supprimer l’image actuelle
                                </label>
                            </div>
                        @endif

                        <input type="file"
                               name="image"
                               class="form-control @error('image') is-invalid @enderror"
                               accept="image/jpeg,image/png,image/jpg,image/webp"
                               id="imageInput">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                        Statut
                    </h5>
                </div>
                <div class="modern-card-body">
                    <div class="form-check form-switch">
                        <input class="form-check-input"
                               type="checkbox"
                               name="is_active"
                               id="is_active"
                               value="1"
                               @checked(old('is_active', $room->is_active))>
                        <label class="form-check-label ms-2" for="is_active">
                            <strong>Salle active</strong>
                            <br>
                            <small class="text-muted">
                                Une salle active peut être réservée pour des réunions.
                            </small>
                        </label>
                    </div>

                    @if($room->is_occupied)
                        <div class="alert alert-warning mt-3 mb-0 small">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            Cette salle est actuellement occupée par une réunion.
                        </div>
                    @endif
                </div>
            </div>

            {{-- Informations --}}
            <div class="modern-card mb-4 bg-light">
                <div class="modern-card-body">
                    <h6 class="text-muted mb-3">
                        <i class="bi bi-info-circle me-1"></i>
                        Informations
                    </h6>
                    <div class="small">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Créée le :</span>
                            <span>{{ $room->created_at?->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Modifiée le :</span>
                            <span>{{ $room->updated_at?->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Réunions aujourd’hui :</span>
                            <span class="badge bg-primary">{{ $room->today_meetings_count }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="modern-card">
                <div class="modern-card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-modern btn-modern-primary">
                            <i class="bi bi-check-circle me-1"></i>
                            Enregistrer les modifications
                        </button>
                        <a href="{{ route('rooms.show', $room) }}" class="btn btn-modern btn-modern-secondary">
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
    const removeImageCheckbox = document.getElementById('removeImage');

    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) {
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

                if (removeImageCheckbox) {
                    removeImageCheckbox.checked = false;
                }
            };
            reader.readAsDataURL(file);
        });
    }

    if (removeImageCheckbox) {
        removeImageCheckbox.addEventListener('change', function () {
            if (this.checked) {
                imagePreview.innerHTML = `
                    <div class="text-center text-muted">
                        <i class="bi bi-image fs-1"></i>
                        <p class="small mb-0">Image supprimée (sera retirée lors de l’enregistrement).</p>
                    </div>
                `;
            }
        });
    }
});
</script>
@endpush
@endsection
