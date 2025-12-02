@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center">
    <div class="card shadow-lg border-0" style="max-width: 800px; width: 100%;">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Modifier le participant</h5>
                <div class="small">
                    <a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Accueil</a>
                    <span class="text-muted">/</span>
                    <a href="{{ route('participants.index') }}" class="text-decoration-none text-muted">Participants</a>
                    <span class="text-muted">/</span>
                    <span class="text-muted">{{ $participant->full_name }}</span>
                </div>
            </div>
            <a href="{{ route('participants.show', $participant) }}" class="btn btn-sm btn-outline-secondary">
                Retour
            </a>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('participants.update', $participant) }}">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nom</label>
                        <input type="text" name="last_name" class="form-control"
                               value="{{ old('last_name', $participant->last_name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Prénom</label>
                        <input type="text" name="first_name" class="form-control"
                               value="{{ old('first_name', $participant->first_name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Adresse e-mail</label>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email', $participant->email) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="phone" class="form-control"
                               value="{{ old('phone', $participant->phone) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fonction</label>
                        <input type="text" name="position" class="form-control"
                               value="{{ old('position', $participant->position) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Institution / Ministère</label>
                        <input type="text" name="institution" class="form-control"
                               value="{{ old('institution', $participant->institution) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Pays</label>
                        <input type="text" name="country" class="form-control"
                               value="{{ old('country', $participant->country) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Type</label>
                        <select name="is_internal" class="form-select">
                            <option value="1" @selected(old('is_internal', $participant->is_internal) == 1)>Interne CEEAC</option>
                            <option value="0" @selected(old('is_internal', $participant->is_internal) == 0)>Externe</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Statut</label>
                        <select name="is_active" class="form-select">
                            <option value="1" @selected(old('is_active', $participant->is_active) == 1)>Actif</option>
                            <option value="0" @selected(old('is_active', $participant->is_active) == 0)>Inactif</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('participants.show', $participant) }}" class="btn btn-outline-secondary">
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
