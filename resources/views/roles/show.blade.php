@extends('layouts.app')

@section('title', 'Détails du Rôle')

@section('content')
<div class="container-fluid py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Rôles</a></li>
            <li class="breadcrumb-item active">{{ $role->name }}</li>
        </ol>
    </nav>

    {{-- En-tête --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title">
                <i class="bi bi-shield-check text-primary"></i>
                Détails du Rôle : {{ $role->name }}
            </h2>
            @php
                $systemRoles = ['super-admin', 'admin', 'sg', 'dsi', 'staff'];
                $isSystem = in_array($role->name, $systemRoles);
            @endphp
            @if($isSystem)
                <span class="badge bg-warning text-dark mt-2">
                    <i class="bi bi-lock-fill"></i> Rôle Système
                </span>
            @else
                <span class="badge bg-success mt-2">
                    <i class="bi bi-unlock-fill"></i> Rôle Personnalisé
                </span>
            @endif
        </div>
        <div class="btn-group">
            @if(auth()->user() && auth()->user()->hasRole('super-admin'))
                <a href="{{ route('roles.edit', $role) }}" class="btn btn-modern btn-warning">
                    <i class="bi bi-pencil"></i> Modifier les Permissions
                </a>
            @endif
            <a href="{{ route('roles.index') }}" class="btn btn-modern btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    @include('partials.alerts')

    <div class="row g-4">
        {{-- Informations générales --}}
        <div class="col-md-4">
            <div class="modern-card">
                <div class="modern-card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle"></i>
                        Informations
                    </h5>
                </div>
                <div class="modern-card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small">Nom du rôle</label>
                        <p class="mb-0 fw-bold">{{ $role->name }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Nombre de permissions</label>
                        <p class="mb-0">
                            <span class="badge bg-info">
                                {{ $role->permissions->count() }} permission(s)
                            </span>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Nombre d'utilisateurs</label>
                        <p class="mb-0">
                            <span class="badge bg-secondary">
                                {{ $role->users->count() }} utilisateur(s)
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="form-label text-muted small">Date de création</label>
                        <p class="mb-0">{{ $role->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Permissions --}}
        <div class="col-md-8">
            <div class="modern-card">
                <div class="modern-card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-key"></i>
                        Permissions ({{ $role->permissions->count() }})
                    </h5>
                    @if(auth()->user() && auth()->user()->hasRole('super-admin'))
                        <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i> Modifier les Permissions
                        </a>
                    @endif
                </div>
                <div class="modern-card-body">
                    @if($role->permissions->count() > 0)
                        <div class="permissions-list">
                            @foreach($allPermissions as $module => $permissions)
                                @php
                                    $modulePermissions = $permissions->filter(function($p) use ($role) {
                                        return $role->permissions->contains($p);
                                    });
                                @endphp
                                @if($modulePermissions->count() > 0)
                                    <div class="permission-module mb-3">
                                        <div class="card border">
                                            <div class="card-header bg-light">
                                                <strong>{{ ucfirst(str_replace('_', ' ', $module)) }}</strong>
                                                <span class="badge bg-primary ms-2">{{ $modulePermissions->count() }}</span>
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-2">
                                                    @foreach($modulePermissions as $permission)
                                                        <div class="col-md-6">
                                                            <span class="badge bg-success">
                                                                <i class="bi bi-check-circle"></i>
                                                                {{ $permission->name }}
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-shield-x display-4 text-muted"></i>
                            <p class="text-muted mt-3">Aucune permission attribuée</p>
                            @if(auth()->user() && auth()->user()->hasRole('super-admin'))
                                <a href="{{ route('roles.edit', $role) }}" class="btn btn-modern btn-primary">
                                    <i class="bi bi-plus-circle"></i> Ajouter des permissions
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Utilisateurs avec ce rôle --}}
    @if($role->users->count() > 0)
        <div class="modern-card mt-4">
            <div class="modern-card-header">
                <h5 class="mb-0">
                    <i class="bi bi-people"></i>
                    Utilisateurs avec ce rôle ({{ $role->users->count() }})
                </h5>
            </div>
            <div class="modern-card-body">
                <div class="table-responsive">
                    <table class="table table-modern table-hover">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Délégation</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($role->users as $user)
                                <tr>
                                    <td>
                                        <a href="{{ route('users.show', $user) }}">
                                            {{ $user->name }}
                                        </a>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->delegation)
                                            <span class="badge bg-info">{{ $user->delegation->name }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->is_active)
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-danger">Inactif</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

