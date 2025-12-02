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
    @can('create', App\Models\User::class)
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Nouvel utilisateur
    </a>
    @endcan
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
                        <small class="text-muted">Utilisateur{{ $inactiveUsers > 1 ? 's' : '' }} inactif{{ $inactiveUsers > 1 ? 's' : '' }}</small>
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
                <select name="is_active" class="form-select">
                    <option value="">Tous</option>
                    <option value="1" @selected($isActive === '1')>Actifs</option>
                    <option value="0" @selected($isActive === '0')>Inactifs</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i> Filtrer
                    </button>
                    @if(collect([$search, $service, $delegationId, $isActive])->filter()->isNotEmpty())
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
<div class="modern-card">
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
                    @forelse($users as $user)
                        <tr>
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
                                @if($user->is_active)
                                    <span class="badge bg-success text-white">Actif</span>
                                @else
                                    <span class="badge bg-danger text-white">Inactif</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="table-actions">
                                    <a href="{{ route('users.show', $user) }}" 
                                       class="btn btn-sm btn-outline-primary"
                                       data-bs-toggle="tooltip"
                                       title="Voir les détails">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @can('update', $user)
                                    <a href="{{ route('users.edit', $user) }}" 
                                       class="btn btn-sm btn-outline-secondary"
                                       data-bs-toggle="tooltip"
                                       title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @endcan
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
                                @if(collect([$search, $service, $delegationId, $isActive])->filter()->isNotEmpty())
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
</div>

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
    // Initialiser les tooltips Bootstrap
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush

@endsection
