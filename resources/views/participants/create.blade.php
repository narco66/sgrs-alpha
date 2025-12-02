@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center">
    <div class="card shadow-lg border-0" style="max-width: 800px; width: 100%;">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Nouveau participant</h5>
            <a href="{{ route('participants.index') }}" class="btn btn-sm btn-outline-secondary">
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

            <form method="POST" action="{{ route('participants.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nom</label>
                        <input type="text" name="last_name" class="form-control"
                               value="{{ old('last_name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Prénom</label>
                        <input type="text" name="first_name" class="form-control"
                               value="{{ old('first_name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Adresse e-mail</label>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="phone" class="form-control"
                               value="{{ old('phone') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fonction</label>
                        <input type="text" name="position" class="form-control"
                               value="{{ old('position') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Institution / Ministère</label>
                        <input type="text" name="institution" class="form-control"
                               value="{{ old('institution') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Pays</label>
                        <input type="text" name="country" class="form-control"
                               value="{{ old('country') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Type</label>
                        <select name="is_internal" class="form-select">
                            <option value="1" @selected(old('is_internal', 1) == 1)>Interne CEEAC</option>
                            <option value="0" @selected(old('is_internal') === '0')>Externe</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Statut</label>
                        <select name="is_active" class="form-select">
                            <option value="1" @selected(old('is_active', 1) == 1)>Actif</option>
                            <option value="0" @selected(old('is_active') === '0')>Inactif</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('participants.index') }}" class="btn btn-outline-secondary">
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
