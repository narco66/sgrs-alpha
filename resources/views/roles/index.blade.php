@extends('layouts.app')

@section('title', 'Gestion des Rôles')

@section('content')
<div class="container-fluid py-4">
    {{-- En-tête --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
<div>
            <h2 class="page-title">
                <i class="bi bi-shield-check text-primary"></i>
                Gestion des Rôles et Permissions
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
                    <li class="breadcrumb-item active">Rôles</li>
                </ol>
            </nav>
        </div>
        {{-- Bouton de création de rôle : accessible à tout utilisateur autorisé à cette page (Super-Admin) --}}
        <a href="{{ route('roles.create') }}" class="btn btn-modern btn-primary">
            <i class="bi bi-plus-circle"></i> Nouveau Rôle
        </a>
    </div>

    {{-- Filtres --}}
    <div class="modern-card mb-4">
        <div class="modern-card-body">
            <form method="GET" action="{{ route('roles.index') }}" class="row g-3">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="form-label">Rechercher un rôle</label>
                        <input type="text" name="search" class="form-control" 
                               value="{{ $search }}" 
                               placeholder="Nom du rôle...">
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-modern btn-primary me-2">
                        <i class="bi bi-search"></i> Rechercher
                    </button>
                    @if($search)
                        <a href="{{ route('roles.index') }}" class="btn btn-modern btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Réinitialiser
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Liste des rôles --}}
    <div class="modern-card">
        <div class="modern-card-header">
            <h5 class="mb-0">
                <i class="bi bi-list-ul"></i>
                Liste des Rôles ({{ $roles->total() }})
            </h5>
        </div>
        <div class="modern-card-body p-0">
            @if($roles->count() > 0)
                <div class="table-responsive">
                    <table class="table table-modern table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Permissions</th>
                                <th>Utilisateurs</th>
                                <th>Type</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-shield-check text-primary me-2"></i>
                                            <strong>{{ $role->name }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $role->permissions_count ?? $role->permissions->count() }} permission(s)
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $role->users_count ?? $role->users->count() }} utilisateur(s)
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $systemRoles = ['super-admin', 'admin', 'sg', 'dsi', 'staff'];
                                            $isSystem = in_array($role->name, $systemRoles);
                                        @endphp
                                        @if($isSystem)
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-lock-fill"></i> Système
                                            </span>
                                        @else
                                            <span class="badge bg-success">
                                                <i class="bi bi-unlock-fill"></i> Personnalisé
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @php
                                            // Utiliser l'utilisateur passé depuis le contrôleur ou auth()
                                            $user = $currentUser ?? auth()->user();
                                            $systemRoles = ['super-admin', 'admin', 'sg', 'dsi', 'staff'];
                                            $isSystem = in_array($role->name, $systemRoles);
                                            
                                            // Vérification directe des rôles
                                            $canEdit = false;
                                            $canDelete = false;
                                            
                                            if ($user) {
                                                // S'assurer que les rôles sont chargés
                                                if (!$user->relationLoaded('roles')) {
                                                    $user->load('roles');
                                                }
                                                $userRoles = $user->roles->pluck('name')->toArray();
                                                $canEdit = in_array('super-admin', $userRoles);
                                                $canDelete = in_array('super-admin', $userRoles) && !$isSystem;
                                            }
                                        @endphp
                                        <div class="btn-group" role="group">
                                            @if($canEdit)
                                                <a href="{{ route('roles.show', $role) }}" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   title="Voir les détails et gérer les permissions">
                                                    <i class="bi bi-eye"></i> Voir
                                                </a>
                                                <a href="{{ route('roles.edit', $role) }}" 
                                                   class="btn btn-sm btn-outline-warning" 
                                                   title="Modifier les permissions">
                                                    <i class="bi bi-pencil"></i> Modifier
                                                </a>
                                            @else
                                                <span class="text-muted small">
                                                    <i class="bi bi-lock"></i> Réservé au Super-Admin
                                                </span>
                                            @endif
                                            @if($canDelete)
                                                <form action="{{ route('roles.destroy', $role) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce rôle ? Cette action est irréversible.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-danger" 
                                                            title="Supprimer">
                                                        <i class="bi bi-trash"></i> Supprimer
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($roles->hasPages())
                    <div class="modern-card-footer">
                        <div class="small text-muted">
                            Affichage de {{ $roles->firstItem() }} à {{ $roles->lastItem() }} 
                            sur {{ $roles->total() }} rôle(s)
                        </div>
                        <div class="pagination-modern">
                            {{ $roles->appends(request()->query())->links() }}
                        </div>
                    </div>
                @endif
            @else
                <div class="empty-state text-center py-5">
                    <i class="bi bi-shield-x display-1 text-muted"></i>
                    <h5 class="mt-3">Aucun rôle trouvé</h5>
                    <p class="text-muted">Commencez par créer un nouveau rôle.</p>
                    <a href="{{ route('roles.create') }}" class="btn btn-modern btn-primary mt-3">
                        <i class="bi bi-plus-circle"></i> Créer un rôle
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
