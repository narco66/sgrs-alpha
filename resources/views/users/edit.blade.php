@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Modifier l'utilisateur</h4>
        <p class="text-muted mb-0">
            Modifiez les informations de {{ $user->name }}.
        </p>
    </div>
    <a href="{{ route('users.show', $user) }}" class="btn btn-outline-secondary">
        Retour au profil
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        @include('partials.alerts')

        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf
            @method('PUT')

            @php
                $canManageRoles = auth()->user()->hasAnyRole(['super-admin', 'admin', 'dsi']) || auth()->user()->can('users.manage');
            @endphp

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nom complet <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <label class="form-label">Prénom</label>
                    <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
                           value="{{ old('first_name', $user->first_name) }}">
                    @error('first_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nom de famille</label>
                    <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                           value="{{ old('last_name', $user->last_name) }}">
                    @error('last_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <label class="form-label">Nouveau mot de passe</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                    <div class="form-text">Laissez vide pour ne pas modifier</div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
            </div>

            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <label class="form-label">Délégation</label>
                    <select name="delegation_id" class="form-select @error('delegation_id') is-invalid @enderror">
                        <option value="">Aucune (Fonctionnaire CEEAC)</option>
                        @foreach($delegations as $delegation)
                            <option value="{{ $delegation->id }}" @selected(old('delegation_id', $user->delegation_id) == $delegation->id)>
                                {{ $delegation->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('delegation_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Service</label>
                    <input type="text" name="service" class="form-control @error('service') is-invalid @enderror"
                           value="{{ old('service', $user->service) }}">
                    @error('service')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            @if($canManageRoles)
                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <label class="form-label">Rôles</label>
                        <select name="roles[]" class="form-select @error('roles') is-invalid @enderror" multiple>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" @selected(in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())))>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('roles')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Permissions directes</label>
                        <select name="permissions[]" class="form-select @error('permissions') is-invalid @enderror" multiple>
                            @foreach($permissions as $permission)
                                <option value="{{ $permission->id }}" @selected(in_array($permission->id, old('permissions', $user->permissions->pluck('id')->toArray())))>
                                    {{ $permission->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('permissions')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            @endif

            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('users.show', $user) }}" class="btn btn-outline-secondary">
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

