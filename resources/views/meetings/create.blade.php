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
                <button class="nav-link" id="logistics-tab" data-bs-toggle="tab" data-bs-target="#logistics" type="button" role="tab">
                    <i class="bi bi-truck"></i> Note logistique
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
                                <div class="input-group">
                                    <select name="meeting_type_id"
                                            id="meeting_type_id"
                                            class="form-select @error('meeting_type_id') is-invalid @enderror"
                                            required
                                            @if(auth()->user()->can('meeting_types.create')) ondblclick="window.quickCreateMeetingType && window.quickCreateMeetingType();" @endif>
                                        <option value="">Sélectionner un type</option>
                                        @foreach($meetingTypes as $type)
                                            <option value="{{ $type->id }}" @selected(old('meeting_type_id') == $type->id)>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if(auth()->user()->can('meeting_types.create'))
                                        <button type="button"
                                                class="btn btn-outline-primary"
                                                id="btnQuickCreateMeetingType"
                                                title="Créer un nouveau type de réunion">
                                            <i class="bi bi-plus-circle"></i>
                                        </button>
                                    @endif
                                </div>
                                <small class="form-text text-muted">
                                    @if(auth()->user()->can('meeting_types.create'))
                                        Double-cliquez sur la liste ou utilisez le bouton + pour ajouter un nouveau type sans quitter ce formulaire.
                                    @endif
                                </small>
                                @error('meeting_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Comité</label>
                                <div class="input-group">
                                    <select name="committee_id"
                                            id="committee_id"
                                            class="form-select @error('committee_id') is-invalid @enderror"
                                            @if(auth()->user()->can('committees.create')) ondblclick="window.quickCreateCommittee && window.quickCreateCommittee();" @endif>
                                        <option value="">Aucun comité</option>
                                        @foreach($committees as $committee)
                                            <option value="{{ $committee->id }}" @selected(old('committee_id') == $committee->id)>
                                                {{ $committee->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if(auth()->user()->can('committees.create'))
                                        <button type="button"
                                                class="btn btn-outline-primary"
                                                id="btnQuickCreateCommittee"
                                                title="Créer un nouveau comité">
                                            <i class="bi bi-plus-circle"></i>
                                        </button>
                                    @endif
                                </div>
                                <small class="form-text text-muted">
                                    @if(auth()->user()->can('committees.create'))
                                        Double-cliquez sur la liste ou utilisez le bouton + pour créer un nouveau comité.
                                    @endif
                                </small>
                                @error('committee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Date et heure de début --}}
                            <div class="col-md-3">
                                <label class="form-label">Date de début <span class="text-danger">*</span></label>
                                <input type="date"
                                       name="date"
                                       class="form-control @error('date') is-invalid @enderror"
                                       value="{{ old('date', now()->format('Y-m-d')) }}"
                                       required>
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Heure de début <span class="text-danger">*</span></label>
                                <input type="time"
                                       name="time"
                                       class="form-control @error('time') is-invalid @enderror"
                                       value="{{ old('time') }}"
                                       required>
                                @error('time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Date et heure de fin --}}
                            <div class="col-md-3">
                                <label class="form-label">Date de fin <span class="text-danger">*</span></label>
                                <input type="date"
                                       name="end_date"
                                       class="form-control @error('end_date') is-invalid @enderror"
                                       value="{{ old('end_date', now()->format('Y-m-d')) }}"
                                       required>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Heure de fin <span class="text-danger">*</span></label>
                                <input type="time"
                                       name="end_time"
                                       class="form-control @error('end_time') is-invalid @enderror"
                                       value="{{ old('end_time') }}"
                                       required>
                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Configuration, pays hôte et Salle --}}
                            <div class="col-md-6">
                                <label class="form-label">Configuration <span class="text-danger">*</span></label>
                                <select name="configuration"
                                        id="configuration"
                                        class="form-select @error('configuration') is-invalid @enderror"
                                        required>
                                    <option value="presentiel" @selected(old('configuration') === 'presentiel')>Présentiel</option>
                                    <option value="hybride" @selected(old('configuration') === 'hybride')>Hybride</option>
                                    <option value="visioconference" @selected(old('configuration') === 'visioconference')>Visioconférence</option>
                                </select>
                                @error('configuration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Pays hôte</label>
                                <input type="text"
                                       name="host_country"
                                       class="form-control @error('host_country') is-invalid @enderror"
                                       placeholder="Ex: République du Congo"
                                       value="{{ old('host_country') }}">
                                @error('host_country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Indiquez le pays hôte si la réunion se tient dans un État membre.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Salle de réunion</label>
                                <div class="input-group">
                                    <select name="room_id"
                                            id="room_id"
                                            class="form-select @error('room_id') is-invalid @enderror"
                                            @if(auth()->user()->can('create', \App\Models\Room::class)) ondblclick="window.quickCreateRoom && window.quickCreateRoom();" @endif>
                                        <option value="">Sélectionner une salle (optionnel)</option>
                                        @foreach($rooms as $room)
                                            <option value="{{ $room->id }}" @selected(old('room_id') == $room->id)>
                                                {{ $room->name }} @if($room->capacity) ({{ $room->capacity }} places) @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @can('create', \App\Models\Room::class)
                                        <button type="button"
                                                class="btn btn-outline-primary"
                                                id="btnQuickCreateRoom"
                                                title="Créer une nouvelle salle de réunion">
                                            <i class="bi bi-plus-circle"></i>
                                        </button>
                                    @endcan
                                </div>
                                <small class="form-text text-muted">
                                    @can('create', \App\Models\Room::class)
                                        Double-cliquez sur la liste ou utilisez le bouton + pour créer rapidement une salle.
                                    @endcan
                                </small>
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

            {{-- ONGLET 2: Note Logistique --}}
            <div class="tab-pane fade" id="logistics" role="tabpanel">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="bi bi-truck text-primary"></i> Éléments de la note logistique
                        </h5>
                        <p class="text-muted mb-4">
                            Renseignez les informations logistiques de la réunion. Ces données seront utilisées pour générer la note logistique officielle.
                        </p>

                        <div class="row g-4">
                            {{-- Transport --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-airplane text-primary"></i> Transport des délégations
                                </label>
                                <textarea name="logistics_transport"
                                          class="form-control @error('logistics_transport') is-invalid @enderror"
                                          rows="4"
                                          placeholder="Moyens de transport, navettes, contacts...">{{ old('logistics_transport') }}</textarea>
                                @error('logistics_transport')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Hébergement --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-building text-primary"></i> Hébergement
                                </label>
                                <textarea name="logistics_accommodation"
                                          class="form-control @error('logistics_accommodation') is-invalid @enderror"
                                          rows="4"
                                          placeholder="Hôtels, réservations, contacts...">{{ old('logistics_accommodation') }}</textarea>
                                @error('logistics_accommodation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Restauration --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-cup-hot text-primary"></i> Restauration
                                </label>
                                <textarea name="logistics_catering"
                                          class="form-control @error('logistics_catering') is-invalid @enderror"
                                          rows="4"
                                          placeholder="Repas officiels, traiteurs, menus...">{{ old('logistics_catering') }}</textarea>
                                @error('logistics_catering')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Pauses café --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-cup text-primary"></i> Pauses café
                                </label>
                                <textarea name="logistics_coffee_breaks"
                                          class="form-control @error('logistics_coffee_breaks') is-invalid @enderror"
                                          rows="4"
                                          placeholder="Organisation des pauses café...">{{ old('logistics_coffee_breaks') }}</textarea>
                                @error('logistics_coffee_breaks')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Disposition de la salle --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-layout-text-window text-primary"></i> Disposition de la salle
                                </label>
                                <textarea name="logistics_room_setup"
                                          class="form-control @error('logistics_room_setup') is-invalid @enderror"
                                          rows="4"
                                          placeholder="Configuration, plan de salle, agencement...">{{ old('logistics_room_setup') }}</textarea>
                                @error('logistics_room_setup')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Matériel audio/visuel --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-display text-primary"></i> Matériel audio/visuel
                                </label>
                                <textarea name="logistics_av_equipment"
                                          class="form-control @error('logistics_av_equipment') is-invalid @enderror"
                                          rows="4"
                                          placeholder="Équipements, besoins techniques...">{{ old('logistics_av_equipment') }}</textarea>
                                @error('logistics_av_equipment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Interprètes --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-translate text-primary"></i> Interprètes
                                </label>
                                <textarea name="logistics_interpreters"
                                          class="form-control @error('logistics_interpreters') is-invalid @enderror"
                                          rows="4"
                                          placeholder="Langues, effectifs, cabines...">{{ old('logistics_interpreters') }}</textarea>
                                @error('logistics_interpreters')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Agents de liaison --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-person-badge text-primary"></i> Agents de liaison
                                </label>
                                <textarea name="logistics_liaison_officers"
                                          class="form-control @error('logistics_liaison_officers') is-invalid @enderror"
                                          rows="4"
                                          placeholder="Contacts, responsabilités...">{{ old('logistics_liaison_officers') }}</textarea>
                                @error('logistics_liaison_officers')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Sécurité --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-shield-check text-primary"></i> Sécurité
                                </label>
                                <textarea name="logistics_security"
                                          class="form-control @error('logistics_security') is-invalid @enderror"
                                          rows="4"
                                          placeholder="Dispositif de sécurité, accès...">{{ old('logistics_security') }}</textarea>
                                @error('logistics_security')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Santé / Dispositif médical --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-heart-pulse text-primary"></i> Santé / Dispositif médical
                                </label>
                                <textarea name="logistics_medical"
                                          class="form-control @error('logistics_medical') is-invalid @enderror"
                                          rows="4"
                                          placeholder="Premiers secours, contacts médicaux...">{{ old('logistics_medical') }}</textarea>
                                @error('logistics_medical')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Protocole d'accueil --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-person-check text-primary"></i> Protocole d'accueil
                                </label>
                                <textarea name="logistics_protocol"
                                          class="form-control @error('logistics_protocol') is-invalid @enderror"
                                          rows="4"
                                          placeholder="Cérémonies, accueil VIP...">{{ old('logistics_protocol') }}</textarea>
                                @error('logistics_protocol')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Autres rubriques --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-three-dots text-primary"></i> Autres éléments
                                </label>
                                <textarea name="logistics_other"
                                          class="form-control @error('logistics_other') is-invalid @enderror"
                                          rows="4"
                                          placeholder="Autres éléments logistiques...">{{ old('logistics_other') }}</textarea>
                                @error('logistics_other')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Notes générales --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-sticky text-primary"></i> Notes et observations générales
                                </label>
                                <textarea name="logistics_notes"
                                          class="form-control @error('logistics_notes') is-invalid @enderror"
                                          rows="4"
                                          placeholder="Observations, points d'attention...">{{ old('logistics_notes') }}</textarea>
                                @error('logistics_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ONGLET 3: Comité d'organisation --}}
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
                                          value="{{ old('terms_host_country', old('host_country')) }}">
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

{{-- Modales de création rapide (types, comités, salles) --}}
@if(auth()->user()->can('meeting_types.create'))
    <div class="modal fade" id="quickCreateMeetingTypeModal" tabindex="-1" aria-labelledby="quickCreateMeetingTypeLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="quickMeetingTypeForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="quickCreateMeetingTypeLabel">
                            <i class="bi bi-plus-circle me-1"></i> Nouveau type de réunion
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nom du type <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control" maxlength="10" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="quick_type_is_active" value="1" checked>
                            <label class="form-check-label" for="quick_type_is_active">
                                Activer ce type de réunion
                            </label>
                        </div>
                        <small class="text-muted d-block mt-2">
                            Les options avancées (couleur, validation, ordre) peuvent être ajustées plus tard dans le module des types de réunion.
                        </small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@if(auth()->user()->can('committees.create'))
    <div class="modal fade" id="quickCreateCommitteeModal" tabindex="-1" aria-labelledby="quickCreateCommitteeLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="quickCommitteeForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="quickCreateCommitteeLabel">
                            <i class="bi bi-plus-circle me-1"></i> Nouveau comité
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nom du comité <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control" maxlength="20" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Type de réunion associé</label>
                            <select name="meeting_type_id" class="form-select">
                                <option value="">Aucun</option>
                                @foreach($meetingTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="quick_committee_is_active" value="1" checked>
                            <label class="form-check-label" for="quick_committee_is_active">
                                Activer ce comité
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@can('create', \App\Models\Room::class)
    <div class="modal fade" id="quickCreateRoomModal" tabindex="-1" aria-labelledby="quickCreateRoomLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="quickRoomForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="quickCreateRoomLabel">
                            <i class="bi bi-plus-circle me-1"></i> Nouvelle salle de réunion
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nom de la salle <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control" maxlength="50" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Capacité (nombre de places) <span class="text-danger">*</span></label>
                            <input type="number" name="capacity" class="form-control" min="1" max="1000" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Localisation</label>
                            <input type="text" name="location" class="form-control">
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="quick_room_is_active" value="1" checked>
                            <label class="form-check-label" for="quick_room_is_active">
                                Activer cette salle
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endcan

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('meetingForm');
    
    // Fonction pour afficher un message de confirmation temporaire
    function showSuccessMessage(message, tabName) {
        const tabButton = document.querySelector(`#${tabName}-tab`);
        if (tabButton) {
            const originalHtml = tabButton.innerHTML;
            const icon = tabButton.querySelector('i').outerHTML;
            tabButton.innerHTML = `${icon} ${message}`;
            tabButton.classList.add('text-success');
            
            setTimeout(() => {
                tabButton.innerHTML = originalHtml;
                tabButton.classList.remove('text-success');
            }, 3000);
        }
    }

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
    const generalHostInput = document.querySelector('input[name=\"host_country\"]');
    const termsHostInput = document.querySelector('input[name=\"terms_host_country\"]');

    function syncHostCountryToTerms() {
        if (!createTermsCheckbox || !createTermsCheckbox.checked || !termsHostInput) {
            return;
        }

        const hostValue = (generalHostInput?.value || '').trim();
        const currentTermsValue = (termsHostInput.value || '').trim();
        const autoFilledValue = termsHostInput.dataset.autoFilledValue || '';

        // Auto-fill only if empty or previously auto-filled to avoid overriding user edits.
        if (hostValue && (currentTermsValue === '' || currentTermsValue === autoFilledValue)) {
            termsHostInput.value = hostValue;
            termsHostInput.dataset.autoFilledValue = hostValue;
        }
    }

    if (createTermsCheckbox && termsFields) {
        createTermsCheckbox.addEventListener('change', function() {
            if (this.checked) {
                termsFields.style.display = 'block';
                syncHostCountryToTerms();
                // Validation du pays hôte si onglet actif
                const hostCountryInput = document.querySelector('input[name="terms_host_country"]');
                if (hostCountryInput && !hostCountryInput.value.trim()) {
                    hostCountryInput.focus();
                }
            } else {
                termsFields.style.display = 'none';
            }
        });
    }

    if (generalHostInput) {
        generalHostInput.addEventListener('input', syncHostCountryToTerms);
        generalHostInput.addEventListener('change', syncHostCountryToTerms);
    }

    // Initial sync on load (useful when checkbox is pre-checked or host already saisi).
    syncHostCountryToTerms();

    // Validation des onglets avant soumission avec messages spécifiques
    form.addEventListener('submit', function(e) {
        let isValid = true;
        let errorMessage = '';
        let errorTab = 'general';

        // Validation onglet général
        const title = document.querySelector('input[name="title"]');
        const meetingType = document.querySelector('select[name="meeting_type_id"]');
        const date = document.querySelector('input[name="date"]');
        const time = document.querySelector('input[name="time"]');
        
        if (!title || !title.value.trim()) {
            isValid = false;
            errorMessage = 'Le titre de la réunion est obligatoire.';
            errorTab = 'general';
        } else if (!meetingType || !meetingType.value) {
            isValid = false;
            errorMessage = 'Le type de réunion est obligatoire.';
            errorTab = 'general';
        } else if (!date || !date.value) {
            isValid = false;
            errorMessage = 'La date de la réunion est obligatoire.';
            errorTab = 'general';
        } else if (!time || !time.value) {
            isValid = false;
            errorMessage = 'L\'heure de la réunion est obligatoire.';
            errorTab = 'general';
        }

        // Validation onglet comité
        if (isValid && useExisting && useExisting.checked) {
            const committeeSelect = document.getElementById('organization_committee_select');
            if (committeeSelect && !committeeSelect.value) {
                isValid = false;
                errorMessage = 'Veuillez sélectionner un comité d\'organisation ou choisir une autre option.';
                errorTab = 'committee';
            }
        }

        if (isValid && createNew && createNew.checked) {
            const newCommitteeName = document.querySelector('input[name="new_committee_name"]');
            if (newCommitteeName && !newCommitteeName.value.trim()) {
                isValid = false;
                errorMessage = 'Le nom du nouveau comité d\'organisation est obligatoire.';
                errorTab = 'committee';
            }
        }

        if (!isValid) {
            e.preventDefault();
            // Afficher l'erreur
            if (!document.querySelector('.alert-danger')) {
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-danger alert-dismissible fade show';
                alertDiv.innerHTML = `
                    <strong>Erreur de validation :</strong> ${errorMessage}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                form.insertBefore(alertDiv, form.firstChild);
            }
            
            // Activer l'onglet avec l'erreur
            const tabButton = document.querySelector(`#${errorTab}-tab`);
            if (tabButton) {
                const tab = new bootstrap.Tab(tabButton);
                tab.show();
            }
            
            return false;
        }

        // Afficher message de confirmation avant soumission
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enregistrement en cours...';
            submitBtn.disabled = true;
        }
    });

    // Bouton "Enregistrer comme brouillon"
    const saveDraftBtn = document.getElementById('saveDraftBtn');
    if (saveDraftBtn) {
        saveDraftBtn.addEventListener('click', function() {
            // Validation minimale pour brouillon
            const title = document.querySelector('input[name="title"]');
            if (!title || !title.value.trim()) {
                alert('Veuillez remplir au moins le titre de la réunion pour enregistrer un brouillon.');
                const tabButton = document.querySelector('#general-tab');
                if (tabButton) {
                    const tab = new bootstrap.Tab(tabButton);
                    tab.show();
                }
                return false;
            }

            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = 'draft';
            form.appendChild(statusInput);
            
            // Message de confirmation
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enregistrement du brouillon...';
            this.disabled = true;
            form.submit();
        });
    }

    // Sauvegarder l'onglet actif avant soumission
    const tabs = document.querySelectorAll('#meetingTabs button[data-bs-toggle="tab"]');
    tabs.forEach(tab => {
        tab.addEventListener('shown.bs.tab', function() {
            const activeTab = this.getAttribute('data-bs-target').replace('#', '');
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
                
                let html = `<h6 class="mb-2">${name || 'Comité sélectionné'}</h6>`;
                
                if (description) {
                    html += `<p class="text-muted small mb-2">${description}</p>`;
                }
                
                if (hostCountry) {
                    html += `<p class="mb-2"><i class="bi bi-geo-alt"></i> <strong>Pays hôte :</strong> ${hostCountry}</p>`;
                }
                
                html += `<p class="mb-0"><i class="bi bi-people"></i> <strong>Membres :</strong> ${membersCount || 0} membre${membersCount > 1 ? 's' : ''}</p>`;
                
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

    // Afficher les messages de succès de session s'ils existent
    @if(session('success'))
        const successAlert = document.createElement('div');
        successAlert.className = 'alert alert-success alert-dismissible fade show';
        successAlert.innerHTML = `
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        form.insertBefore(successAlert, form.firstChild);
        
        // Activer l'onglet approprié si spécifié
        @if(session('active_tab'))
            const activeTabFromSession = '{{ session("active_tab") }}';
            if (activeTabFromSession && activeTabFromSession !== 'general') {
                const tabButton = document.querySelector(`#${activeTabFromSession}-tab`);
                if (tabButton) {
                    const tab = new bootstrap.Tab(tabButton);
                    tab.show();
                }
            }
        @endif
    @endif

    // -------- Création rapide : Types de réunion, Comités, Salles --------
    const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

    // Helper générique pour POST JSON
    async function postJson(url, formEl) {
        const formData = new FormData(formEl);
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: formData,
        });
        if (!response.ok) {
            let message = 'Erreur lors de l\'enregistrement.';
            try {
                const data = await response.json();
                if (data.message) {
                    message = data.message;
                }
            } catch (e) {}
            throw new Error(message);
        }
        return await response.json();
    }

    // ---- Type de réunion ----
    @if(auth()->user()->can('meeting_types.create'))
    (function() {
        const typeSelect = document.getElementById('meeting_type_id');
        const btn = document.getElementById('btnQuickCreateMeetingType');
        const modalEl = document.getElementById('quickCreateMeetingTypeModal');
        const formQuick = document.getElementById('quickMeetingTypeForm');
        if (!modalEl || !formQuick || !typeSelect) return;
        const modal = new bootstrap.Modal(modalEl);

        window.quickCreateMeetingType = function() {
            formQuick.reset();
            modal.show();
        };

        if (btn) {
            btn.addEventListener('click', function() {
                window.quickCreateMeetingType();
            });
        }

        formQuick.addEventListener('submit', async function(e) {
            e.preventDefault();
            try {
                const data = await postJson('{{ route('meeting-types.store') }}', formQuick);
                if (data && data.id && data.name) {
                    const option = new Option(data.name, data.id, true, true);
                    typeSelect.add(option);
                    typeSelect.value = data.id;
                }
                modal.hide();
            } catch (error) {
                alert(error.message || 'Erreur lors de la création du type de réunion.');
            }
        });
    })();
    @endif

    // ---- Comité ----
    @if(auth()->user()->can('committees.create'))
    (function() {
        const committeeSelect = document.getElementById('committee_id');
        const btn = document.getElementById('btnQuickCreateCommittee');
        const modalEl = document.getElementById('quickCreateCommitteeModal');
        const formQuick = document.getElementById('quickCommitteeForm');
        if (!modalEl || !formQuick || !committeeSelect) return;
        const modal = new bootstrap.Modal(modalEl);

        window.quickCreateCommittee = function() {
            formQuick.reset();
            // Pré-sélectionner le type de réunion actuel dans le formulaire rapide si possible
            const mainTypeSelect = document.getElementById('meeting_type_id');
            const quickTypeSelect = formQuick.querySelector('select[name="meeting_type_id"]');
            if (mainTypeSelect && quickTypeSelect) {
                quickTypeSelect.value = mainTypeSelect.value || '';
            }
            modal.show();
        };

        if (btn) {
            btn.addEventListener('click', function() {
                window.quickCreateCommittee();
            });
        }

        formQuick.addEventListener('submit', async function(e) {
            e.preventDefault();
            try {
                const data = await postJson('{{ route('committees.store') }}', formQuick);
                if (data && data.id && data.name) {
                    const option = new Option(data.name, data.id, true, true);
                    committeeSelect.add(option);
                    committeeSelect.value = data.id;
                }
                modal.hide();
            } catch (error) {
                alert(error.message || 'Erreur lors de la création du comité.');
            }
        });
    })();
    @endif

    // ---- Salle de réunion ----
    @can('create', \App\Models\Room::class)
    (function() {
        const roomSelect = document.getElementById('room_id');
        const btn = document.getElementById('btnQuickCreateRoom');
        const modalEl = document.getElementById('quickCreateRoomModal');
        const formQuick = document.getElementById('quickRoomForm');
        if (!modalEl || !formQuick || !roomSelect) return;
        const modal = new bootstrap.Modal(modalEl);

        window.quickCreateRoom = function() {
            formQuick.reset();
            modal.show();
        };

        if (btn) {
            btn.addEventListener('click', function() {
                window.quickCreateRoom();
            });
        }

        formQuick.addEventListener('submit', async function(e) {
            e.preventDefault();
            try {
                const data = await postJson('{{ route('rooms.store') }}', formQuick);
                if (data && data.id && data.name) {
                    const option = new Option(data.name, data.id, true, true);
                    roomSelect.add(option);
                    roomSelect.value = data.id;
                }
                modal.hide();
            } catch (error) {
                alert(error.message || 'Erreur lors de la création de la salle.');
            }
        });
    })();
    @endcan
});
</script>
@endpush
@endsection
