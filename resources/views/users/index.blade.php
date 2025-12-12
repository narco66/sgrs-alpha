@extends('layouts.app')

@section('title', 'Gestion des utilisateurs')

@section('content')
{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
        <li class="breadcrumb-item active" aria-current="page">Utilisateurs</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold">Utilisateurs</h3>
        <p class="text-muted mb-0 small">
            Accueil / Utilisateurs
        </p>
    </div>
    @php
        $canAdminUsersHeader = auth()->user()->hasAnyRole(['super-admin', 'admin', 'dsi'])
            || auth()->user()->can('users.manage');
    @endphp
    @if($canAdminUsersHeader)
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Nouvel utilisateur
        </a>
    @endif
</div>

@include('partials.alerts')

{{-- STATISTIQUES RAPIDES --}}

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="kpi-card">
            <div class="d-flex align-items-center">
                <div class="kpi-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-people"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="kpi-value">{{ $totalUsers }}</div>
                    <div class="kpi-label">Utilisateur{{ $totalUsers > 1 ? 's' : '' }} total</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-success bg-opacity-10 rounded p-3">
                            <i class="bi bi-person-check text-success fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="mb-0 fw-bold">{{ $activeUsers }}</h5>
                        <small class="text-muted">Utilisateur{{ $activeUsers > 1 ? 's' : '' }} actif{{ $activeUsers > 1 ? 's' : '' }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-danger bg-opacity-10 rounded p-3">
                            <i class="bi bi-person-x text-danger fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="mb-0 fw-bold">{{ $inactiveUsers }}</h5>
                        <small class="text-muted">Utilisateur{{ $inactiveUsers > 1 ? 's' : '' }} inactif{{ $inactiveUsers > 1 ? 's' : '' }} (dont comptes en attente)</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-info bg-opacity-10 rounded p-3">
                            <i class="bi bi-shield-check text-info fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="mb-0 fw-bold">{{ $totalRoles }}</h5>
                        <small class="text-muted">Rôle{{ $totalRoles > 1 ? 's' : '' }} attribué{{ $totalRoles > 1 ? 's' : '' }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- FILTRES DE RECHERCHE --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0">
        <h6 class="mb-0 fw-semibold">
            <i class="bi bi-funnel me-2"></i>Filtres de recherche
        </h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('users.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Recherche</label>
                <input type="text" name="q" class="form-control"
                       value="{{ $search }}" 
                       placeholder="Nom, prénom, email ou service">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Service</label>
                <select name="service" class="form-select">
                    <option value="">Tous</option>
                    @foreach($services as $svc)
                        <option value="{{ $svc }}" @selected($service === $svc)>{{ $svc }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Délégation</label>
                <select name="delegation_id" class="form-select">
                    <option value="">Toutes</option>
                    @foreach($delegations as $del)
                        <option value="{{ $del->id }}" @selected($delegationId == $del->id)>{{ $del->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Statut</label>
                <select name="status" class="form-select">
                    <option value="">Tous</option>
                    <option value="active" @selected($status === 'active')>Actifs</option>
                    <option value="pending" @selected($status === 'pending')>En attente</option>
                    <option value="rejected" @selected($status === 'rejected')>Rejetés</option>
                    <option value="inactive" @selected($status === 'inactive')>Inactifs</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i> Filtrer
                    </button>
                    @if(collect([$search, $service, $delegationId, $status])->filter()->isNotEmpty())
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

{{-- TABLEAU DES UTILISATEURS --}}
<div class="modern-card" id="user-status-root">
    <div class="modern-card-header">
        <h5 class="modern-card-title">
            <i class="bi bi-list-ul"></i>
            Liste des utilisateurs
        </h5>
        <span class="badge-modern badge-modern-primary">
            {{ $users->total() }} résultat{{ $users->total() > 1 ? 's' : '' }}
        </span>
    </div>
    <div class="modern-card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="sortable">
                            Nom
                            <i class="bi bi-arrow-down-up"></i>
                        </th>
                        <th class="sortable">
                            Prénom
                            <i class="bi bi-arrow-down-up"></i>
                        </th>
                        <th class="sortable">
                            Service
                            <i class="bi bi-arrow-down-up"></i>
                        </th>
                        <th class="sortable">
                            Délégation
                            <i class="bi bi-arrow-down-up"></i>
                        </th>
                        <th class="sortable">
                            Email
                            <i class="bi bi-arrow-down-up"></i>
                        </th>
                        <th class="sortable">
                            Rôle
                            <i class="bi bi-arrow-down-up"></i>
                        </th>
                        <th class="sortable">
                            Statut
                            <i class="bi bi-arrow-down-up"></i>
                        </th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $canAdminUsers = auth()->user()->hasAnyRole(['super-admin', 'admin', 'dsi'])
                            || auth()->user()->can('users.manage');
                    @endphp
                    @forelse($users as $user)
                        <tr data-user-id="{{ $user->id }}">
                            <td class="fw-semibold">{{ $user->last_name ?: ($user->name ?: '—') }}</td>
                            <td>{{ $user->first_name ?: '—' }}</td>
                            <td>
                                @if($user->service)
                                    {{ $user->service }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($user->delegation)
                                    {{ $user->delegation->title }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-muted small">{{ Str::limit($user->email, 20) }}</span>
                            </td>
                            <td>
                                @if($user->roles->isNotEmpty())
                                    @php
                                        $firstRole = $user->roles->first();
                                        $roleColors = [
                                            'super-admin' => 'bg-danger text-white',
                                            'admin' => 'bg-primary text-white',
                                            'administrateur' => 'bg-danger text-white',
                                            'sg' => 'bg-success text-white',
                                            'dsi' => 'bg-info text-white',
                                            'fonctionnaire' => 'bg-primary text-white',
                                            'staff' => 'bg-secondary text-white',
                                            'invite' => 'bg-warning text-dark',
                                        ];
                                        $roleColor = $roleColors[$firstRole->name] ?? 'bg-secondary text-white';
                                    @endphp
                                    <span class="badge {{ $roleColor }}">{{ $firstRole->name }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $status = $user->status ?? ($user->is_active ? 'active' : 'inactive');
                                    $statusLabel = [
                                        'active'   => 'Actif',
                                        'pending'  => 'En attente de validation',
                                        'rejected' => 'Rejeté',
                                        'inactive' => 'Inactif',
                                    ][$status] ?? ucfirst($status);
                                    $statusClass = match($status) {
                                        'active'   => 'bg-success text-white',
                                        'pending'  => 'bg-warning text-dark',
                                        'rejected' => 'bg-danger text-white',
                                        default    => 'bg-secondary text-white',
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                            </td>
                            <td class="text-end">
                                <div class="table-actions d-flex gap-1 justify-content-end">
                                    <a href="{{ route('users.show', $user) }}" 
                                       class="btn btn-sm btn-outline-primary"
                                       data-bs-toggle="tooltip"
                                       title="Voir les détails">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    @if($canAdminUsers)
                                        {{-- Validation rapide depuis la liste pour les comptes en attente --}}
                                        @if(($user->status ?? 'inactive') === 'pending')
                                            <form action="{{ route('users.approve', $user) }}"
                                                  method="POST"
                                                  class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-sm btn-success"
                                                        data-bs-toggle="tooltip"
                                                        title="Valider et activer ce compte">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('users.edit', $user) }}" 
                                           class="btn btn-sm btn-outline-secondary"
                                           data-bs-toggle="tooltip"
                                           title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endif

                                    @can('delete', $user)
                                    <form action="{{ route('users.destroy', $user) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="tooltip"
                                                title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                                <p class="text-muted mb-0">Aucun utilisateur trouvé.</p>
                                @if(collect([$search, $service, $delegationId, $status])->filter()->isNotEmpty())
                                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-primary mt-2">
                                        Réinitialiser les filtres
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
        <div class="modern-card-footer">
            <div class="small text-muted">
                Affichage de {{ $users->firstItem() }} à {{ $users->lastItem() }} 
                sur {{ $users->total() }} utilisateur{{ $users->total() > 1 ? 's' : '' }}
            </div>
            <div class="pagination-modern">
                {{ $users->appends(request()->query())->links() }}
            </div>
        </div>
    @endif
{{-- Plus de modale JS complexe pour la validation rapide : validation simple par bouton dans la ligne --}}

@push('styles')
<style>
    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    .table tbody tr {
        transition: all 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .badge {
        font-weight: 500;
        padding: 0.4em 0.75em;
    }
</style>
@endpush

@push('scripts')
<script>
    // Initialiser les tooltips Bootstrap (comportement léger et prévisible)
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>

{{-- Modale de mise à jour statut + rôles --}}
<div class="modal fade" id="userStatusModal" tabindex="-1" aria-labelledby="userStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userStatusModalLabel">
                    <i class="bi bi-person-check me-1 text-primary"></i>
                    Validation / mise à jour du compte utilisateur
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Nom complet</label>
                        <div class="form-control-plaintext" x-text="user.name"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Email</label>
                        <div class="form-control-plaintext" x-text="user.email"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Statut actuel</label>
                        <div class="form-control-plaintext" x-text="statusLabel"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Date de création</label>
                        <div class="form-control-plaintext" x-text="user.created_at"></div>
                    </div>
                </div>

                <hr>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">
                            Nouveau statut
                            <span class="text-danger">*</span>
                        </label>
                        <select class="form-select"
                                x-model="user.status">
                            <option value="active">Actif</option>
                            <option value="inactive">Inactif</option>
                            <option value="pending">En attente de validation</option>
                            <option value="rejected">Rejeté</option>
                        </select>
                        <template x-if="errors.status">
                            <div class="invalid-feedback d-block" x-text="errors.status[0]"></div>
                        </template>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">
                            Rôles à attribuer
                        </label>
                        <select class="form-select"
                                multiple
                                size="5"
                                x-model="user.roles">
                            <template x-for="role in allRoles" :key="role.id">
                                <option :value="role.id" x-text="role.name"></option>
                            </template>
                        </select>
                        <div class="form-text">
                            Laissez vide pour conserver les rôles actuels.
                        </div>
                        <template x-if="errors.roles">
                            <div class="invalid-feedback d-block" x-text="errors.roles[0]"></div>
                        </template>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Annuler
                </button>
                <button type="button" class="btn btn-primary" @click="save()" :disabled="saving">
                    <span x-show="!saving">
                        <i class="bi bi-check-circle me-1"></i>
                        Mettre à jour
                    </span>
                    <span x-show="saving">
                        <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                        Enregistrement...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
@endpush

@endsection
