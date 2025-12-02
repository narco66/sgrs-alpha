@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 fw-bold">Modifier la réunion</h2>
            <p class="text-muted mb-0 small">{{ $meeting->title }}</p>
        </div>
        <a href="{{ route('meetings.show', $meeting) }}" class="btn btn-outline-secondary">
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

    <form method="POST" action="{{ route('meetings.update', $meeting) }}" id="meetingForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Navigation par onglets --}}
        @php
            $activeTab = session('active_tab', 'general');
        @endphp
        <ul class="nav nav-tabs mb-4" id="meetingTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab === 'general' ? 'active' : '' }}" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">
                    <i class="bi bi-info-circle"></i> Informations générales
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab === 'committee' ? 'active' : '' }}" id="committee-tab" data-bs-toggle="tab" data-bs-target="#committee" type="button" role="tab">
                    <i class="bi bi-people"></i> Comité d'organisation
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab === 'terms' ? 'active' : '' }}" id="terms-tab" data-bs-toggle="tab" data-bs-target="#terms" type="button" role="tab">
                    <i class="bi bi-file-earmark-text"></i> Cahier des charges
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab === 'delegations' ? 'active' : '' }}" id="delegations-tab" data-bs-toggle="tab" data-bs-target="#delegations" type="button" role="tab">
                    <i class="bi bi-building"></i> Délégations
                </button>
            </li>
        </ul>

        <div class="tab-content" id="meetingTabsContent">
            {{-- ONGLET 1: Informations générales --}}
            <div class="tab-pane fade {{ $activeTab === 'general' ? 'show active' : '' }}" id="general" role="tabpanel">
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
                                       value="{{ old('title', $meeting->title) }}"
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
                                        <option value="{{ $type->id }}" @selected(old('meeting_type_id', $meeting->meeting_type_id) == $type->id)>
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
                                        <option value="{{ $committee->id }}" @selected(old('committee_id', $meeting->committee_id) == $committee->id)>
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
                                       value="{{ old('date', $meeting->start_at ? $meeting->start_at->format('Y-m-d') : now()->format('Y-m-d')) }}"
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
                                       value="{{ old('time', $meeting->start_at ? $meeting->start_at->format('H:i') : '') }}"
                                       required>
                                @error('time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Durée <span class="text-danger">*</span></label>
                                <select name="duration_minutes" class="form-select @error('duration_minutes') is-invalid @enderror" required>
                                    @foreach([30, 60, 90, 120, 180, 240] as $minutes)
                                        <option value="{{ $minutes }}" @selected(old('duration_minutes', $meeting->duration_minutes ?? 60) == $minutes)>
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
                                    <option value="presentiel" @selected(old('configuration', $meeting->configuration ?? 'presentiel') === 'presentiel')>Présentiel</option>
                                    <option value="hybride" @selected(old('configuration', $meeting->configuration ?? 'presentiel') === 'hybride')>Hybride</option>
                                    <option value="visioconference" @selected(old('configuration', $meeting->configuration ?? 'presentiel') === 'visioconference')>Visioconférence</option>
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
                                        <option value="{{ $room->id }}" @selected(old('room_id', $meeting->room_id) == $room->id)>
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
                                          placeholder="Description de la réunion, ordre du jour préliminaire, etc.">{{ old('description', $meeting->description) }}</textarea>
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
                                          placeholder="Points à l'ordre du jour (un par ligne)">{{ old('agenda', $meeting->agenda) }}</textarea>
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
                                        <option value="{{ $val }}" @selected(old('reminder_minutes_before', $meeting->reminder_minutes_before ?? 0) == $val)>
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
            <div class="tab-pane fade {{ $activeTab === 'committee' ? 'show active' : '' }}" id="committee" role="tabpanel">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="bi bi-people text-primary"></i> Comité d'organisation de la réunion
                        </h5>
                        <p class="text-muted mb-4">
                            Le comité d'organisation est composé de fonctionnaires de la CEEAC et, si la réunion se tient dans un État membre, 
                            de fonctionnaires du pays hôte.
                        </p>

                        @php
                            $currentCommittee = $meeting->organizationCommittee;
                            $committeeOption = old('committee_option', $currentCommittee ? 'existing' : '');
                        @endphp

                        <div class="row g-3">
                            {{-- Comité existant ou nouveau --}}
                            <div class="col-md-12">
                                <label class="form-label">Comité d'organisation <span class="text-muted small">(optionnel)</span></label>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="committee_option" id="no_committee" value="" @checked($committeeOption === '')>
                                    <label class="form-check-label" for="no_committee">
                                        Aucun comité
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="committee_option" id="use_existing_committee" value="existing" @checked($committeeOption === 'existing')>
                                    <label class="form-check-label" for="use_existing_committee">
                                        Utiliser un comité existant
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="committee_option" id="create_new_committee" value="new" @checked($committeeOption === 'new')>
                                    <label class="form-check-label" for="create_new_committee">
                                        Créer un nouveau comité d'organisation
                                    </label>
                                </div>
                            </div>

                            {{-- Sélection d'un comité existant --}}
                            <div class="col-md-12" id="existing_committee_section" style="display: {{ $committeeOption === 'existing' ? 'block' : 'none' }};">
                                <label class="form-label">Sélectionner un comité <span class="text-danger">*</span></label>
                                <select name="organization_committee_id" class="form-select @error('organization_committee_id') is-invalid @enderror">
                                    <option value="">Sélectionner un comité</option>
                                    @foreach($availableCommittees as $committee)
                                        <option value="{{ $committee->id }}" @selected(old('organization_committee_id', $currentCommittee?->id) == $committee->id)>
                                            {{ $committee->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('organization_committee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <a href="{{ route('organization-committees.create', ['meeting_id' => $meeting->id]) }}" target="_blank" class="text-decoration-none">
                                        <i class="bi bi-plus-circle"></i> Créer un nouveau comité
                                    </a>
                                </div>
                            </div>

                            {{-- Formulaire pour créer un nouveau comité --}}
                            <div class="col-md-12" id="new_committee_section" style="display: {{ $committeeOption === 'new' ? 'block' : 'none' }};">
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle"></i> 
                                    Le comité d'organisation sera créé et associé à cette réunion.
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
                                    <textarea name="new_committee_description" 
                                              class="form-control" 
                                              rows="3" 
                                              placeholder="Description du comité d'organisation">{{ old('new_committee_description') }}</textarea>
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

                            {{-- Affichage du comité actuel et de ses membres --}}
                            @if($currentCommittee)
                                <div class="col-12 mt-4">
                                    <div class="card border-primary">
                                        <div class="card-header bg-primary text-white">
                                            <h6 class="mb-0">
                                                <i class="bi bi-people-fill"></i> Comité d'organisation actuel
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <h5 class="mb-1">{{ $currentCommittee->name }}</h5>
                                                @if($currentCommittee->description)
                                                    <p class="text-muted small mb-2">{{ $currentCommittee->description }}</p>
                                                @endif
                                                @if($currentCommittee->host_country)
                                                    <p class="mb-0">
                                                        <i class="bi bi-geo-alt"></i> 
                                                        <strong>Pays hôte :</strong> {{ $currentCommittee->host_country }}
                                                    </p>
                                                @endif
                                            </div>

                                            @if($currentCommittee->members->count() > 0)
                                                <div class="mb-3">
                                                    <h6 class="mb-2">
                                                        <i class="bi bi-person-badge"></i> 
                                                        Membres du comité ({{ $currentCommittee->members->count() }})
                                                    </h6>
                                                    <div class="table-responsive">
                                                        <table class="table table-sm table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th>Nom</th>
                                                                    <th>Type</th>
                                                                    <th>Rôle</th>
                                                                    <th>Service/Département</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($currentCommittee->members as $member)
                                                                    <tr>
                                                                        <td>
                                                                            <strong>{{ $member->user->name ?? 'N/A' }}</strong>
                                                                            @if($member->user->email)
                                                                                <br><small class="text-muted">{{ $member->user->email }}</small>
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @if($member->member_type === 'ceeac')
                                                                                <span class="badge bg-primary">CEEAC</span>
                                                                            @elseif($member->member_type === 'host_country')
                                                                                <span class="badge bg-success">Pays hôte</span>
                                                                            @else
                                                                                <span class="badge bg-secondary">{{ $member->member_type ?? 'N/A' }}</span>
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            <span class="badge bg-info">{{ $member->role ?? 'Membre' }}</span>
                                                                        </td>
                                                                        <td>
                                                                            @if($member->department || $member->service)
                                                                                {{ $member->department ?? '' }}
                                                                                @if($member->department && $member->service) - @endif
                                                                                {{ $member->service ?? '' }}
                                                                            @else
                                                                                <span class="text-muted">—</span>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="alert alert-warning mb-0">
                                                    <i class="bi bi-exclamation-triangle"></i> 
                                                    Aucun membre n'a encore été ajouté à ce comité.
                                                </div>
                                            @endif

                                            <div class="mt-3">
                                                <a href="{{ route('organization-committees.show', $currentCommittee) }}" 
                                                   class="btn btn-sm btn-outline-primary" target="_blank">
                                                    <i class="bi bi-eye"></i> Voir les détails complets
                                                </a>
                                                <a href="{{ route('organization-committees.edit', $currentCommittee) }}" 
                                                   class="btn btn-sm btn-outline-secondary" target="_blank">
                                                    <i class="bi bi-pencil"></i> Modifier le comité
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- ONGLET 3: Cahier des charges --}}
            <div class="tab-pane fade {{ $activeTab === 'terms' ? 'show active' : '' }}" id="terms" role="tabpanel">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="bi bi-file-earmark-text text-primary"></i> Cahier des charges
                        </h5>
                        <p class="text-muted mb-4">
                            Le cahier des charges définit le partage des responsabilités et des charges financières/logistiques 
                            entre la CEEAC et le pays hôte.
                        </p>

                        @php
                            $termsOfReference = $meeting->termsOfReference;
                            $hasTerms = $termsOfReference !== null;
                        @endphp

                        @if($hasTerms)
                            <div class="alert alert-success mb-4">
                                <i class="bi bi-check-circle"></i> 
                                <strong>Un cahier des charges existe déjà pour cette réunion.</strong>
                                <a href="{{ route('terms-of-reference.show', $meeting) }}" class="btn btn-sm btn-outline-primary ms-2" target="_blank">
                                    <i class="bi bi-eye"></i> Voir le cahier des charges
                                </a>
                            </div>
                        @else
                            <div class="alert alert-info mb-4">
                                <i class="bi bi-info-circle"></i> 
                                Aucun cahier des charges n'a encore été créé pour cette réunion.
                            </div>
                        @endif

                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="create_terms_of_reference" 
                                           id="create_terms_of_reference" 
                                           value="1"
                                           @checked(old('create_terms_of_reference') || (!$hasTerms && old('create_terms_of_reference')))>
                                    <label class="form-check-label" for="create_terms_of_reference">
                                        {{ $hasTerms ? 'Créer une nouvelle version du cahier des charges' : 'Créer un cahier des charges maintenant' }}
                                    </label>
                                </div>
                            </div>

                            <div id="terms_fields" style="display: {{ old('create_terms_of_reference') || (!$hasTerms && old('create_terms_of_reference')) ? 'block' : 'none' }};">
                                <div class="col-md-6">
                                    <label class="form-label">Pays hôte <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="terms_host_country" 
                                           class="form-control @error('terms_host_country') is-invalid @enderror" 
                                           placeholder="Ex: République du Congo"
                                           value="{{ old('terms_host_country', $termsOfReference?->host_country) }}">
                                    @error('terms_host_country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Date de signature prévue</label>
                                    <input type="date" 
                                           name="terms_signature_date" 
                                           class="form-control" 
                                           value="{{ old('terms_signature_date', $termsOfReference?->signature_date?->format('Y-m-d')) }}">
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Responsabilités CEEAC</label>
                                    <textarea name="terms_responsibilities_ceeac" 
                                              class="form-control" 
                                              rows="4" 
                                              placeholder="Décrivez les responsabilités de la CEEAC...">{{ old('terms_responsibilities_ceeac', $termsOfReference?->responsibilities_ceeac) }}</textarea>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Responsabilités pays hôte</label>
                                    <textarea name="terms_responsibilities_host" 
                                              class="form-control" 
                                              rows="4" 
                                              placeholder="Décrivez les responsabilités du pays hôte...">{{ old('terms_responsibilities_host', $termsOfReference?->responsibilities_host) }}</textarea>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Partage financier</label>
                                    <textarea name="terms_financial_sharing" 
                                              class="form-control" 
                                              rows="3" 
                                              placeholder="Décrivez le partage des charges financières...">{{ old('terms_financial_sharing', $termsOfReference?->financial_sharing) }}</textarea>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Partage logistique</label>
                                    <textarea name="terms_logistical_sharing" 
                                              class="form-control" 
                                              rows="3" 
                                              placeholder="Décrivez le partage des charges logistiques...">{{ old('terms_logistical_sharing', $termsOfReference?->logistical_sharing) }}</textarea>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">
                                        <i class="bi bi-file-earmark-pdf"></i> Document physique signé (optionnel)
                                    </label>
                                    @if($termsOfReference && $termsOfReference->signed_document_path)
                                        <div class="alert alert-info mb-2">
                                            <i class="bi bi-file-earmark-check"></i> 
                                            <strong>Document actuel :</strong> 
                                            {{ $termsOfReference->signed_document_original_name }}
                                            <a href="{{ route('terms-of-reference.download-signed', [$meeting, $termsOfReference]) }}" 
                                               class="btn btn-sm btn-outline-primary ms-2">
                                                <i class="bi bi-download"></i> Télécharger
                                            </a>
                                        </div>
                                    @endif
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
            <div class="tab-pane fade {{ $activeTab === 'delegations' ? 'show active' : '' }}" id="delegations" role="tabpanel">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="bi bi-building text-primary"></i> Délégations participantes
                        </h5>
                        <p class="text-muted mb-4">
                            <strong>Important :</strong> La participation se fait par délégations institutionnelles (États membres, 
                            organisations internationales, partenaires), et non par participants individuels.
                        </p>

                        @php
                            $delegations = $meeting->delegations;
                        @endphp

                        @if($delegations->count() > 0)
                            <div class="alert alert-success mb-4">
                                <i class="bi bi-check-circle"></i> 
                                <strong>{{ $delegations->count() }} délégation(s) participent à cette réunion.</strong>
                            </div>

                            {{-- Liste des délégations avec leurs membres --}}
                            <div class="row g-3 mb-4">
                                @foreach($delegations as $delegation)
                                    <div class="col-12">
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-header bg-light">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-0">
                                                            <i class="bi bi-building"></i> 
                                                            <strong>{{ $delegation->title }}</strong>
                                                        </h6>
                                                        @if($delegation->country || $delegation->organization_name)
                                                            <small class="text-muted">
                                                                @if($delegation->entity_type === 'state_member' && $delegation->country)
                                                                    <i class="bi bi-geo-alt"></i> {{ $delegation->country }}
                                                                @elseif($delegation->organization_name)
                                                                    <i class="bi bi-building"></i> {{ $delegation->organization_name }}
                                                                @endif
                                                            </small>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        @php
                                                            $entityTypes = [
                                                                'state_member' => 'État membre',
                                                                'international_organization' => 'Organisation internationale',
                                                                'technical_partner' => 'Partenaire technique',
                                                                'financial_partner' => 'Partenaire financier',
                                                                'other' => 'Autre'
                                                            ];
                                                            $statusColors = [
                                                                'invited' => 'warning',
                                                                'confirmed' => 'success',
                                                                'registered' => 'info',
                                                                'present' => 'primary',
                                                                'absent' => 'danger',
                                                                'excused' => 'secondary'
                                                            ];
                                                        @endphp
                                                        <span class="badge bg-secondary me-2">
                                                            {{ $entityTypes[$delegation->entity_type] ?? $delegation->entity_type }}
                                                        </span>
                                                        <span class="badge bg-{{ $statusColors[$delegation->participation_status] ?? 'secondary' }}">
                                                            {{ ucfirst($delegation->participation_status) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                @if($delegation->head_of_delegation_name)
                                                    <div class="mb-3">
                                                        <strong>Chef de délégation :</strong> 
                                                        {{ $delegation->head_of_delegation_name }}
                                                        @if($delegation->head_of_delegation_position)
                                                            <span class="text-muted">({{ $delegation->head_of_delegation_position }})</span>
                                                        @endif
                                                    </div>
                                                @endif

                                                @if($delegation->members->count() > 0)
                                                    <div class="mb-3">
                                                        <h6 class="mb-2">
                                                            <i class="bi bi-people"></i> 
                                                            Membres de la délégation ({{ $delegation->members->count() }})
                                                        </h6>
                                                        <div class="table-responsive">
                                                            <table class="table table-sm table-hover mb-0">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Nom</th>
                                                                        <th>Fonction</th>
                                                                        <th>Rôle</th>
                                                                        <th>Statut</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($delegation->members->sortBy(function($member) {
                                                                        return $member->role === 'head' ? 0 : 1;
                                                                    }) as $member)
                                                                        <tr>
                                                                            <td>
                                                                                <strong>{{ $member->full_name }}</strong>
                                                                                @if($member->isHead())
                                                                                    <span class="badge bg-primary ms-1">Chef</span>
                                                                                @endif
                                                                                @if($member->email)
                                                                                    <br><small class="text-muted">{{ $member->email }}</small>
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                {{ $member->position ?? '—' }}
                                                                                @if($member->title)
                                                                                    <br><small class="text-muted">{{ $member->title }}</small>
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                <span class="badge bg-info">
                                                                                    {{ ucfirst($member->role) }}
                                                                                </span>
                                                                            </td>
                                                                            <td>
                                                                                @php
                                                                                    $memberStatusColors = [
                                                                                        'invited' => 'warning',
                                                                                        'confirmed' => 'success',
                                                                                        'present' => 'primary',
                                                                                        'absent' => 'danger',
                                                                                        'excused' => 'secondary'
                                                                                    ];
                                                                                @endphp
                                                                                <span class="badge bg-{{ $memberStatusColors[$member->status] ?? 'secondary' }}">
                                                                                    {{ ucfirst($member->status) }}
                                                                                </span>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="alert alert-warning mb-0">
                                                        <i class="bi bi-exclamation-triangle"></i> 
                                                        Aucun membre n'a encore été ajouté à cette délégation.
                                                    </div>
                                                @endif

                                                <div class="mt-3">
                                                    <a href="{{ route('delegations.show', $delegation) }}" 
                                                       class="btn btn-sm btn-outline-primary" target="_blank">
                                                        <i class="bi bi-eye"></i> Voir les détails complets
                                                    </a>
                                                    <a href="{{ route('delegations.edit', $delegation) }}" 
                                                       class="btn btn-sm btn-outline-secondary" target="_blank">
                                                        <i class="bi bi-pencil"></i> Modifier
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-warning mb-4">
                                <i class="bi bi-exclamation-triangle"></i> 
                                Aucune délégation n'a encore été ajoutée à cette réunion.
                            </div>
                        @endif

                        <div class="row g-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Gérer les délégations</h6>
                                    <a href="{{ route('delegations.create', ['meeting_id' => $meeting->id]) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-plus-circle"></i> Ajouter une délégation
                                    </a>
                                </div>
                                <div class="form-text mt-2">
                                    Les délégations peuvent être ajoutées et gérées depuis la page de détails de la réunion.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Boutons d'action --}}
        <div class="d-flex justify-content-between align-items-center mt-4">
            <a href="{{ route('meetings.show', $meeting) }}" class="btn btn-outline-secondary">
                <i class="bi bi-x-circle"></i> Annuler
            </a>
            <div>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Enregistrer les modifications
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
        if (noCommittee && noCommittee.checked) {
            existingSection.style.display = 'none';
            newSection.style.display = 'none';
        } else if (useExisting && useExisting.checked) {
            existingSection.style.display = 'block';
            newSection.style.display = 'none';
        } else if (createNew && createNew.checked) {
            existingSection.style.display = 'none';
            newSection.style.display = 'block';
        }
    }

    if (noCommittee) noCommittee.addEventListener('change', updateCommitteeSections);
    if (useExisting) useExisting.addEventListener('change', updateCommitteeSections);
    if (createNew) createNew.addEventListener('change', updateCommitteeSections);
    
    // Initialiser l'affichage au chargement
    updateCommitteeSections();

    // Gestion de l'affichage des champs du cahier des charges
    const createTermsCheckbox = document.getElementById('create_terms_of_reference');
    const termsFields = document.getElementById('terms_fields');

    if (createTermsCheckbox && termsFields) {
        createTermsCheckbox.addEventListener('change', function() {
            if (this.checked) {
                termsFields.style.display = 'block';
            } else {
                termsFields.style.display = 'none';
            }
        });
    }

    // Enregistrer l'onglet actif avant soumission
    const form = document.getElementById('meetingForm');
    const tabs = document.querySelectorAll('#meetingTabs button[data-bs-toggle="tab"]');
    
    tabs.forEach(tab => {
        tab.addEventListener('shown.bs.tab', function() {
            const activeTab = this.getAttribute('data-bs-target').replace('#', '');
            // Créer un champ caché pour stocker l'onglet actif
            let hiddenInput = document.querySelector('input[name="active_tab"]');
            if (!hiddenInput) {
                hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'active_tab';
                form.appendChild(hiddenInput);
            }
            hiddenInput.value = activeTab;
        });
    });
    
    // Initialiser l'onglet actif au chargement
    const activeTabFromSession = '{{ $activeTab }}';
    if (activeTabFromSession && activeTabFromSession !== 'general') {
        const tabButton = document.querySelector(`#${activeTabFromSession}-tab`);
        if (tabButton) {
            const tab = new bootstrap.Tab(tabButton);
            tab.show();
        }
    }
});
</script>
@endpush
@endsection
