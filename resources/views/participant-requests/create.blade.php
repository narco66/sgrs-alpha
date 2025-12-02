@extends('layouts.app')

@section('title', 'Créer une demande d\'ajout de participant')

@section('content')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
        <li class="breadcrumb-item"><a href="{{ route('participant-requests.index') }}">Demandes de participants</a></li>
        <li class="breadcrumb-item active">Créer</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold">Créer une demande d'ajout de participant</h3>
        <p class="text-muted mb-0 small">Accueil / Demandes de participants / Créer</p>
    </div>
</div>

@include('partials.alerts')

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('participant-requests.store') }}">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="meeting_id" class="form-label">Réunion <span class="text-danger">*</span></label>
                    <select class="form-select @error('meeting_id') is-invalid @enderror" id="meeting_id" name="meeting_id" required>
                        <option value="">Sélectionner une réunion</option>
                        @if($meeting)
                            <option value="{{ $meeting->id }}" selected>{{ $meeting->title }}</option>
                        @endif
                        @foreach($meetings as $m)
                            <option value="{{ $m->id }}" @selected(old('meeting_id') == $m->id)>{{ $m->title }} - {{ $m->start_at->format('d/m/Y H:i') }}</option>
                        @endforeach
                    </select>
                    @error('meeting_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="participant_name" class="form-label">Nom du participant <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('participant_name') is-invalid @enderror" id="participant_name" name="participant_name" value="{{ old('participant_name') }}" required>
                    @error('participant_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="participant_email" class="form-label">Email du participant</label>
                    <input type="email" class="form-control @error('participant_email') is-invalid @enderror" id="participant_email" name="participant_email" value="{{ old('participant_email') }}">
                    @error('participant_email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="participant_role" class="form-label">Rôle</label>
                    <input type="text" class="form-control @error('participant_role') is-invalid @enderror" id="participant_role" name="participant_role" value="{{ old('participant_role') }}" placeholder="ex: Expert, Observateur">
                    @error('participant_role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mb-3">
                    <label for="justification" class="form-label">Justification <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('justification') is-invalid @enderror" id="justification" name="justification" rows="3" required placeholder="Expliquez pourquoi ce participant doit être ajouté à la réunion">{{ old('justification') }}</textarea>
                    @error('justification')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('participant-requests.index') }}" class="btn btn-outline-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-send me-1"></i> Soumettre la demande
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

