@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 fw-bold">Créer une nouvelle réunion</h2>
            <p class="text-muted mb-0 small">Conforme au modèle institutionnel CEEAC</p>
        </div>
        <a href="{{ route('meetings.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Erreurs détectées :</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('meetings.store') }}" id="meetingForm" enctype="multipart/form-data">
        @csrf

        {{-- Navigation par onglets --}}
        <ul class="nav nav-tabs mb-4" id="meetingTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">
                    <i class="bi bi-info-circle"></i> Informations générales
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="committee-tab" data-bs-toggle="tab" data-bs-target="#committee" type="button" role="tab">
                    <i class="bi bi-people"></i> Comité d'organisation
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="terms-tab" data-bs-toggle="tab" data-bs-target="#terms" type="button" role="tab">
                    <i class="bi bi-file-earmark-text"></i> Cahier des charges
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="delegations-tab" data-bs-toggle="tab" data-bs-target="#delegations" type="button" role="tab">
                    <i class="bi bi-building"></i> Délégations
                </button>
            </li>
        </ul>

        <div class="tab-content" id="meetingTabsContent">
            {{-- ONGLET 1: Informations générales --}}
            <div class="tab-pane fade show active" id="general" role="tabpanel">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="bi bi-info-circle text-primary"></i> Informations de la réunion
                        </h5>

                        <div class="row g-3">
                            {{-- Titre --}}
                            <div class="col-md-12">
                                <label class="form-label">Titre de la réunion <span class="text-danger">*</span></label>
                                <input type="text"
                                       name="title"
                                       class="form-control @error('title') is-invalid @enderror"
                                       placeholder="Ex: Réunion du Conseil des Ministres de la CEEAC"
                                       value="{{ old('title') }}"
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Type de réunion et Comité --}}
                            <div class="col-md-6">
                                <label class="form-label">Type de réunion <span class="text-danger">*</span></label>
                                <select name="meeting_type_id" class="form-select @error('meeting_type_id') is-invalid @enderror" required>
                                    <option value="">Sélectionner un type</option>
                                    @foreach($meetingTypes as $type)
                                        <option value="{{ $type->id }}" @selected(old('meeting_type_id') == $type->id)>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('meeting_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Comité</label>
                                <select name="committee_id" class="form-select @error('committee_id') is-invalid @enderror">
                                    <option value="">Aucun comité</option>
                                    @foreach($committees as $committee)
                                        <option value="{{ $committee->id }}" @selected(old('committee_id') == $committee->id)>
                                            {{ $committee->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('committee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Date et Heure --}}
                            <div class="col-md-4">
                                <label class="form-label">Date <span class="text-danger">*</span></label>
                                <input type="date"
                                       name="date"
                                       class="form-control @error('date') is-invalid @enderror"
                                       value="{{ old('date', now()->format('Y-m-d')) }}"
                                       required>
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Heure <span class="text-danger">*</span></label>
                                <input type="time"
                                       name="time"
                                       class="form-control @error('time') is-invalid @enderror"
                                       value="{{ old('time') }}"
                                       required>
                                @error('time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Durée <span class="text-danger">*</span></label>
                                <select name="duration_minutes" class="form-select @error('duration_minutes') is-invalid @enderror" required>
                                    @foreach([30, 60, 90, 120, 180, 240] as $minutes)
                                        <option value="{{ $minutes }}" @selected(old('duration_minutes', 60) == $minutes)>
                                            {{ $minutes }} minutes
                                        </option>
                                    @endforeach
                                </select>
                                @error('duration_minutes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Configuration et Salle --}}
                            <div class="col-md-6">
                                <label class="form-label">Configuration <span class="text-danger">*</span></label>
                                <select name="configuration" class="form-select @error('configuration') is-invalid @enderror" required>
                                    <option value="presentiel" @selected(old('configuration') === 'presentiel')>Présentiel</option>
                                    <option value="hybride" @selected(old('configuration') === 'hybride')>Hybride</option>
                                    <option value="visioconference" @selected(old('configuration') === 'visioconference')>Visioconférence</option>
                                </select>
                                @error('configuration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Salle de réunion</label>
                                <select name="room_id" class="form-select @error('room_id') is-invalid @enderror">
                                    <option value="">Sélectionner une salle (optionnel)</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" @selected(old('room_id') == $room->id)>
                                            {{ $room->name }} @if($room->capacity) ({{ $room->capacity }} places) @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('room_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Description --}}
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea name="description"
                                          rows="4"
                                          class="form-control @error('description') is-invalid @enderror"
                                          placeholder="Description de la réunion, ordre du jour préliminaire, etc.">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Ordre du jour --}}
                            <div class="col-12">
                                <label class="form-label">Ordre du jour</label>
                                <textarea name="agenda"
                                          rows="6"
                                          class="form-control @error('agenda') is-invalid @enderror"
                                          placeholder="Points à l'ordre du jour (un par ligne)">{{ old('agenda') }}</textarea>
                                @error('agenda')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Rappel --}}
                            <div class="col-md-6">
                                <label class="form-label">Rappel avant la réunion</label>
                                <select name="reminder_minutes_before" class="form-select">
                                    @php
                                        $reminderValues = [0 => 'Aucun', 5 => '5 minutes', 10 => '10 minutes', 15 => '15 minutes', 30 => '30 minutes', 60 => '1 heure', 120 => '2 heures', 1440 => '1 jour'];
                                    @endphp
                                    @foreach($reminderValues as $val => $label)
                                        <option value="{{ $val }}" @selected(old('reminder_minutes_before', 0) == $val)>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ONGLET 2: Comité d'organisation --}}
            <div class="tab-pane fade" id="committee" role="tabpanel">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="bi bi-people text-primary"></i> Comité d'organisation de la réunion
                        </h5>
                        <p class="text-muted mb-4">
                            Le comité d'organisation est composé de fonctionnaires de la CEEAC et, si la réunion se tient dans un État membre, 
                            de fonctionnaires du pays hôte.
                        </p>

                        <div class="row g-3">
                            {{-- Comité existant ou nouveau --}}
                            <div class="col-md-12">
                                <label class="form-label">Comité d'organisation <span class="text-muted small">(optionnel)</span></label>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="committee_option" id="no_committee" value="" checked>
                                    <label class="form-check-label" for="no_committee">
                                        Aucun comité (peut être ajouté plus tard)
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="committee_option" id="use_existing_committee" value="existing">
                                    <label class="form-check-label" for="use_existing_committee">
                                        Utiliser un comité existant
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="committee_option" id="create_new_committee" value="new">
                                    <label class="form-check-label" for="create_new_committee">
                                        Créer un nouveau comité d'organisation
                                    </label>
                                </div>
                            </div>

                            {{-- Sélection d'un comité existant --}}
                            <div class="col-md-12" id="existing_committee_section" style="display: none;">
                                <label class="form-label">Sélectionner un comité <span class="text-danger">*</span></label>
                                <select name="organization_committee_id" 
                                        id="organization_committee_select"
                                        class="form-select @error('organization_committee_id') is-invalid @enderror">
                                    <option value="">Sélectionner un comité</option>
                                    @foreach($availableCommittees as $committee)
                                        <option value="{{ $committee->id }}" 
                                                data-name="{{ $committee->name }}"
                                                data-description="{{ $committee->description ?? '' }}"
                                                data-host-country="{{ $committee->host_country ?? '' }}"
                                                data-members-count="{{ $committee->members->count() }}"
                                                @selected(old('organization_committee_id') == $committee->id)>
                                            {{ $committee->name }} 
                                            @if($committee->members->count() > 0)
                                                ({{ $committee->members->count() }} membre{{ $committee->members->count() > 1 ? 's' : '' }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('organization_committee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <a href="{{ route('organization-committees.create') }}" target="_blank" class="text-decoration-none">
                                        <i class="bi bi-plus-circle"></i> Créer un nouveau comité
                                    </a>
                                </div>

                                {{-- Affichage des informations du comité sélectionné --}}
                                <div id="selected_committee_info" class="mt-3" style="display: none;">
                                    <div class="card border-info">
                                        <div class="card-header bg-info text-white">
                                            <h6 class="mb-0">
                                                <i class="bi bi-info-circle"></i> Informations du comité sélectionné
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div id="committee_info_content">
                                                {{-- Le contenu sera rempli dynamiquement par JavaScript --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Formulaire pour créer un nouveau comité --}}
                            <div class="col-md-12" id="new_committee_section" style="display: none;">
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle"></i> 
                                    Le comité d'organisation sera créé et associé à cette réunion. 
                                    Vous pourrez ajouter les membres après la création de la réunion.
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Nom du comité <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="new_committee_name" 
                                           class="form-control @error('new_committee_name') is-invalid @enderror" 
                                           placeholder="Ex: Comité d'organisation - Réunion des Ministres"
                                           value="{{ old('new_committee_name') }}">
                                    @error('new_committee_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="new_committee_description" class="form-control" rows="3" placeholder="Description du comité d'organisation"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Pays hôte (si applicable)</label>
                                    <input type="text" 
                                           name="new_committee_host_country" 
                                           class="form-control" 
                                           placeholder="Ex: République du Congo"
                                           value="{{ old('new_committee_host_country') }}">
                                    <div class="form-text">Indiquez le pays hôte si la réunion se tient dans un État membre</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ONGLET 3: Cahier des charges --}}
            <div class="tab-pane fade" id="terms" role="tabpanel">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="bi bi-file-earmark-text text-primary"></i> Cahier des charges
                        </h5>
                        <p class="text-muted mb-4">
                            Le cahier des charges définit le partage des responsabilités et des charges financières/logistiques 
                            entre la CEEAC et le pays hôte. Il peut être créé après la création de la réunion.
                        </p>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> 
                            <strong>Note :</strong> Le cahier des charges peut être créé et géré après la création de la réunion 
                            depuis la page de détails de la réunion. Cette étape est optionnelle lors de la création.
                        </div>

                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="create_terms_of_reference" id="create_terms_of_reference" value="1">
                                    <label class="form-check-label" for="create_terms_of_reference">
                                        Créer un cahier des charges maintenant
                                    </label>
                                </div>
                            </div>

                            <div id="terms_fields" style="display: none;">
                                <div class="col-md-6">
                                    <label class="form-label">Pays hôte <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="terms_host_country" 
                                           class="form-control @error('terms_host_country') is-invalid @enderror" 
                                           placeholder="Ex: République du Congo"
                                           value="{{ old('terms_host_country') }}">
                                    @error('terms_host_country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Date de signature prévue</label>
                                    <input type="date" name="terms_signature_date" class="form-control">
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Responsabilités CEEAC</label>
                                    <textarea name="terms_responsibilities_ceeac" 
                                              class="form-control" 
                                              rows="4" 
                                              placeholder="Décrivez les responsabilités de la CEEAC..."></textarea>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Responsabilités pays hôte</label>
                                    <textarea name="terms_responsibilities_host" 
                                              class="form-control" 
                                              rows="4" 
                                              placeholder="Décrivez les responsabilités du pays hôte..."></textarea>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Partage financier</label>
                                    <textarea name="terms_financial_sharing" 
                                              class="form-control" 
                                              rows="3" 
                                              placeholder="Décrivez le partage des charges financières..."></textarea>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Partage logistique</label>
                                    <textarea name="terms_logistical_sharing" 
                                              class="form-control" 
                                              rows="3" 
                                              placeholder="Décrivez le partage des charges logistiques..."></textarea>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">
                                        <i class="bi bi-file-earmark-pdf"></i> Document physique signé (optionnel)
                                    </label>
                                    <input type="file" 
                                           name="terms_signed_document" 
                                           class="form-control"
                                           accept=".pdf,.jpg,.jpeg,.png">
                                    <div class="form-text">
                                        Vous pouvez joindre le document physique signé entre les deux parties (PDF ou image scannée). 
                                        Formats acceptés : PDF, JPG, JPEG, PNG. Taille maximale : 10 MB.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ONGLET 4: Délégations --}}
            <div class="tab-pane fade" id="delegations" role="tabpanel">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="bi bi-building text-primary"></i> Délégations participantes
                        </h5>
                        <p class="text-muted mb-4">
                            <strong>Important :</strong> La participation se fait par délégations institutionnelles (États membres, 
                            organisations internationales, partenaires), et non par participants individuels.
                        </p>

                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> 
                            <strong>Note :</strong> Les délégations peuvent être ajoutées après la création de la réunion 
                            depuis la page de détails. Cette étape est optionnelle lors de la création.
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">Délégations à inviter</h6>
                                    <a href="{{ route('delegations.create') }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-plus-circle"></i> Créer une nouvelle délégation
                                    </a>
                                </div>

                                <div class="form-text mb-3">
                                    Les délégations existantes peuvent être associées à cette réunion après sa création.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Boutons d'action --}}
        <div class="d-flex justify-content-between align-items-center mt-4">
            <a href="{{ route('meetings.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-x-circle"></i> Annuler
            </a>
            <div>
                <button type="button" class="btn btn-outline-primary" id="saveDraftBtn">
                    <i class="bi bi-save"></i> Enregistrer comme brouillon
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Créer la réunion
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion de l'affichage du formulaire de comité
    const noCommittee = document.getElementById('no_committee');
    const useExisting = document.getElementById('use_existing_committee');
    const createNew = document.getElementById('create_new_committee');
    const existingSection = document.getElementById('existing_committee_section');
    const newSection = document.getElementById('new_committee_section');

    function updateCommitteeSections() {
        if (noCommittee.checked) {
            existingSection.style.display = 'none';
            newSection.style.display = 'none';
        } else if (useExisting.checked) {
            existingSection.style.display = 'block';
            newSection.style.display = 'none';
        } else if (createNew.checked) {
            existingSection.style.display = 'none';
            newSection.style.display = 'block';
        }
    }

    noCommittee.addEventListener('change', updateCommitteeSections);
    useExisting.addEventListener('change', updateCommitteeSections);
    createNew.addEventListener('change', updateCommitteeSections);
    
    // Initialiser l'affichage au chargement
    updateCommitteeSections();

    // Gestion de l'affichage des champs du cahier des charges
    const createTermsCheckbox = document.getElementById('create_terms_of_reference');
    const termsFields = document.getElementById('terms_fields');

    createTermsCheckbox.addEventListener('change', function() {
        if (this.checked) {
            termsFields.style.display = 'block';
        } else {
            termsFields.style.display = 'none';
        }
    });

    // Validation des onglets avant soumission
    const form = document.getElementById('meetingForm');
    form.addEventListener('submit', function(e) {
        // Validation basique - vous pouvez ajouter plus de validations ici
        const title = document.querySelector('input[name="title"]').value;
        if (!title.trim()) {
            e.preventDefault();
            alert('Veuillez remplir au moins le titre de la réunion.');
            // Activer l'onglet général
            document.getElementById('general-tab').click();
            return false;
        }
    });

    // Bouton "Enregistrer comme brouillon"
    document.getElementById('saveDraftBtn').addEventListener('click', function() {
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = 'draft';
        form.appendChild(statusInput);
        form.submit();
    });

    // Afficher les informations du comité sélectionné
    const committeeSelect = document.getElementById('organization_committee_select');
    const committeeInfo = document.getElementById('selected_committee_info');
    const committeeInfoContent = document.getElementById('committee_info_content');
    
    if (committeeSelect && committeeInfo && committeeInfoContent) {
        committeeSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            
            if (selectedOption.value) {
                const name = selectedOption.getAttribute('data-name');
                const description = selectedOption.getAttribute('data-description');
                const hostCountry = selectedOption.getAttribute('data-host-country');
                const membersCount = selectedOption.getAttribute('data-members-count');
                
                let html = `<h6 class="mb-2">${name}</h6>`;
                
                if (description) {
                    html += `<p class="text-muted small mb-2">${description}</p>`;
                }
                
                if (hostCountry) {
                    html += `<p class="mb-2"><i class="bi bi-geo-alt"></i> <strong>Pays hôte :</strong> ${hostCountry}</p>`;
                }
                
                html += `<p class="mb-0"><i class="bi bi-people"></i> <strong>Membres :</strong> ${membersCount} membre${membersCount > 1 ? 's' : ''}</p>`;
                
                if (membersCount == 0) {
                    html += `<div class="alert alert-warning mt-2 mb-0"><i class="bi bi-exclamation-triangle"></i> Aucun membre n'a encore été ajouté à ce comité.</div>`;
                }
                
                committeeInfoContent.innerHTML = html;
                committeeInfo.style.display = 'block';
            } else {
                committeeInfo.style.display = 'none';
            }
        });
        
        // Déclencher l'événement au chargement si un comité est déjà sélectionné
        if (committeeSelect.value) {
            committeeSelect.dispatchEvent(new Event('change'));
        }
    }
});
</script>
@endpush
@endsection
