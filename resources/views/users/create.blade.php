@extends('layouts.app')

@section('title', 'Créer un utilisateur')

@section('content')
{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Utilisateurs</a></li>
        <li class="breadcrumb-item active">Créer</li>
    </ol>
</nav>

{{-- En-tête de page --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="page-title mb-1">Créer un utilisateur</h3>
        <p class="text-muted mb-0 small">Accueil / Utilisateurs / Créer</p>
    </div>
    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Retour à la liste
    </a>
</div>

@include('partials.alerts')

<div class="modern-form">
    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        
        <div class="form-section">
            <h5 class="form-section-title">
                <i class="bi bi-person"></i>
                Informations personnelles
            </h5>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="bi bi-person-badge"></i>
                        Nom complet
                        <span class="required">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name') }}"
                           required>
                    @error('name')
                        <div class="invalid-feedback">
                            <i class="bi bi-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="bi bi-envelope"></i>
                        Email
                        <span class="required">*</span>
                    </label>
                    <input type="email" 
                           name="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email') }}"
                           required>
                    @error('email')
                        <div class="invalid-feedback">
                            <i class="bi bi-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="bi bi-person"></i>
                        Prénom
                    </label>
                    <input type="text" 
                           name="first_name" 
                           class="form-control @error('first_name') is-invalid @enderror" 
                           value="{{ old('first_name') }}">
                    @error('first_name')
                        <div class="invalid-feedback">
                            <i class="bi bi-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="bi bi-person"></i>
                        Nom de famille
                    </label>
                    <input type="text" 
                           name="last_name" 
                           class="form-control @error('last_name') is-invalid @enderror" 
                           value="{{ old('last_name') }}">
                    @error('last_name')
                        <div class="invalid-feedback">
                            <i class="bi bi-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
        
        <div class="form-section">
            <h5 class="form-section-title">
                <i class="bi bi-shield-lock"></i>
                Authentification
            </h5>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="bi bi-key"></i>
                        Mot de passe
                        <span class="required">*</span>
                    </label>
                    <input type="password" 
                           name="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           required>
                    @error('password')
                        <div class="invalid-feedback">
                            <i class="bi bi-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                    <div class="form-text">
                        <i class="bi bi-info-circle"></i>
                        Minimum 8 caractères
                    </div>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="bi bi-key-fill"></i>
                        Confirmer le mot de passe
                        <span class="required">*</span>
                    </label>
                    <input type="password" 
                           name="password_confirmation" 
                           class="form-control" 
                           required>
                </div>
            </div>
        </div>
        
        <div class="form-section">
            <h5 class="form-section-title">
                <i class="bi bi-building"></i>
                Organisation
            </h5>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="bi bi-flag"></i>
                        Délégation
                    </label>
                    <select name="delegation_id" class="form-select @error('delegation_id') is-invalid @enderror">
                        <option value="">Aucune (Fonctionnaire CEEAC)</option>
                        @foreach($delegations as $delegation)
                            <option value="{{ $delegation->id }}" @selected(old('delegation_id') == $delegation->id)>
                                {{ $delegation->title }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">
                        <i class="bi bi-info-circle"></i>
                        Laissez vide si c'est un fonctionnaire de la CEEAC
                    </div>
                    @error('delegation_id')
                        <div class="invalid-feedback">
                            <i class="bi bi-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="bi bi-briefcase"></i>
                        Service
                    </label>
                    <input type="text" 
                           name="service" 
                           class="form-control @error('service') is-invalid @enderror" 
                           value="{{ old('service') }}"
                           placeholder="Ex: Direction Générale">
                    <div class="form-text">
                        <i class="bi bi-info-circle"></i>
                        Uniquement pour les fonctionnaires de la CEEAC
                    </div>
                    @error('service')
                        <div class="invalid-feedback">
                            <i class="bi bi-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
        
        <div class="form-section">
            <h5 class="form-section-title">
                <i class="bi bi-shield-check"></i>
                Rôles et permissions
            </h5>
            @php
                $canManageRoles = auth()->user()->hasAnyRole(['super-admin', 'admin', 'dsi']) || auth()->user()->can('users.manage');
            @endphp

            <div class="row g-3">
                @if($canManageRoles)
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="bi bi-person-badge"></i>
                            Rôles
                        </label>
                        <select name="roles[]" class="form-select @error('roles') is-invalid @enderror" multiple size="5">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" @selected(in_array($role->id, old('roles', [])))>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">
                            <i class="bi bi-info-circle"></i>
                            Maintenez Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs rôles
                        </div>
                        @error('roles')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="bi bi-key"></i>
                            Permissions directes
                        </label>
                        <select name="permissions[]" class="form-select @error('permissions') is-invalid @enderror" multiple size="5">
                            @foreach($permissions as $permission)
                                <option value="{{ $permission->id }}" @selected(in_array($permission->id, old('permissions', [])))>
                                    {{ $permission->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">
                            <i class="bi bi-info-circle"></i>
                            Sélectionnez des permissions spécifiques en plus des rôles
                        </div>
                        @error('permissions')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                @endif

                <div class="col-md-6">
                    <label class="form-label">
                        <i class="bi bi-toggle-on"></i>
                        Statut du compte
                    </label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="active" @selected(old('status', 'active') === 'active')>Actif</option>
                        <option value="inactive" @selected(old('status') === 'inactive')>Inactif</option>
                        <option value="pending" @selected(old('status') === 'pending')>En attente de validation</option>
                        <option value="rejected" @selected(old('status') === 'rejected')>Rejeté</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">
                            <i class="bi bi-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-end gap-2 mt-4 pt-4 border-top">
            <a href="{{ route('users.index') }}" class="btn btn-modern btn-modern-secondary">
                <i class="bi bi-x-circle"></i>
                Annuler
            </a>
            <button type="submit" class="btn btn-modern btn-modern-primary">
                <i class="bi bi-check-circle"></i>
                Créer l'utilisateur
            </button>
        </div>
    </form>
</div>
@endsection

