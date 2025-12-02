@extends('layouts.app')

@section('title', 'Créer une demande de réunion')

@section('content')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
        <li class="breadcrumb-item"><a href="{{ route('meeting-requests.index') }}">Demandes de réunion</a></li>
        <li class="breadcrumb-item active">Créer</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold">Créer une demande de réunion</h3>
        <p class="text-muted mb-0 small">Accueil / Demandes de réunion / Créer</p>
    </div>
</div>

@include('partials.alerts')

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('meeting-requests.store') }}">
            @csrf

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="title" class="form-label">Titre de la réunion <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="meeting_type_id" class="form-label">Type de réunion</label>
                    <select class="form-select @error('meeting_type_id') is-invalid @enderror" id="meeting_type_id" name="meeting_type_id">
                        <option value="">Sélectionner un type</option>
                        @foreach($meetingTypes as $type)
                            <option value="{{ $type->id }}" @selected(old('meeting_type_id') == $type->id)>{{ $type->name }}</option>
                        @endforeach
                    </select>
                    @error('meeting_type_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="committee_id" class="form-label">Comité</label>
                    <select class="form-select @error('committee_id') is-invalid @enderror" id="committee_id" name="committee_id">
                        <option value="">Sélectionner un comité</option>
                        @foreach($committees as $committee)
                            <option value="{{ $committee->id }}" @selected(old('committee_id') == $committee->id)>{{ $committee->name }}</option>
                        @endforeach
                    </select>
                    @error('committee_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="requested_start_at" class="form-label">Date et heure de début <span class="text-danger">*</span></label>
                    <input type="datetime-local" class="form-control @error('requested_start_at') is-invalid @enderror" id="requested_start_at" name="requested_start_at" value="{{ old('requested_start_at') }}" required>
                    @error('requested_start_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="requested_end_at" class="form-label">Date et heure de fin</label>
                    <input type="datetime-local" class="form-control @error('requested_end_at') is-invalid @enderror" id="requested_end_at" name="requested_end_at" value="{{ old('requested_end_at') }}">
                    @error('requested_end_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="requested_room_id" class="form-label">Salle demandée</label>
                    <select class="form-select @error('requested_room_id') is-invalid @enderror" id="requested_room_id" name="requested_room_id">
                        <option value="">Sélectionner une salle</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" @selected(old('requested_room_id') == $room->id)>{{ $room->name }}</option>
                        @endforeach
                    </select>
                    @error('requested_room_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="other_location" class="form-label">Autre lieu</label>
                    <input type="text" class="form-control @error('other_location') is-invalid @enderror" id="other_location" name="other_location" value="{{ old('other_location') }}" placeholder="Si la salle n'est pas dans la liste">
                    @error('other_location')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mb-3">
                    <label for="justification" class="form-label">Justification</label>
                    <textarea class="form-control @error('justification') is-invalid @enderror" id="justification" name="justification" rows="3" placeholder="Expliquez pourquoi cette réunion est nécessaire">{{ old('justification') }}</textarea>
                    @error('justification')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('meeting-requests.index') }}" class="btn btn-outline-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-send me-1"></i> Soumettre la demande
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

