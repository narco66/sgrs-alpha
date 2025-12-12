

<?php $__env->startSection('title', 'Gestion des utilisateurs'); ?>

<?php $__env->startSection('content'); ?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Accueil</a></li>
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
    <?php
        $canAdminUsersHeader = auth()->user()->hasAnyRole(['super-admin', 'admin', 'dsi'])
            || auth()->user()->can('users.manage');
    ?>
    <?php if($canAdminUsersHeader): ?>
        <a href="<?php echo e(route('users.create')); ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Nouvel utilisateur
        </a>
    <?php endif; ?>
</div>

<?php echo $__env->make('partials.alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>



<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="kpi-card">
            <div class="d-flex align-items-center">
                <div class="kpi-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-people"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="kpi-value"><?php echo e($totalUsers); ?></div>
                    <div class="kpi-label">Utilisateur<?php echo e($totalUsers > 1 ? 's' : ''); ?> total</div>
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
                        <h5 class="mb-0 fw-bold"><?php echo e($activeUsers); ?></h5>
                        <small class="text-muted">Utilisateur<?php echo e($activeUsers > 1 ? 's' : ''); ?> actif<?php echo e($activeUsers > 1 ? 's' : ''); ?></small>
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
                        <h5 class="mb-0 fw-bold"><?php echo e($inactiveUsers); ?></h5>
                        <small class="text-muted">Utilisateur<?php echo e($inactiveUsers > 1 ? 's' : ''); ?> inactif<?php echo e($inactiveUsers > 1 ? 's' : ''); ?> (dont comptes en attente)</small>
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
                        <h5 class="mb-0 fw-bold"><?php echo e($totalRoles); ?></h5>
                        <small class="text-muted">Rôle<?php echo e($totalRoles > 1 ? 's' : ''); ?> attribué<?php echo e($totalRoles > 1 ? 's' : ''); ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0">
        <h6 class="mb-0 fw-semibold">
            <i class="bi bi-funnel me-2"></i>Filtres de recherche
        </h6>
    </div>
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('users.index')); ?>" class="row g-3">
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Recherche</label>
                <input type="text" name="q" class="form-control"
                       value="<?php echo e($search); ?>" 
                       placeholder="Nom, prénom, email ou service">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Service</label>
                <select name="service" class="form-select">
                    <option value="">Tous</option>
                    <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $svc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($svc); ?>" <?php if($service === $svc): echo 'selected'; endif; ?>><?php echo e($svc); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Délégation</label>
                <select name="delegation_id" class="form-select">
                    <option value="">Toutes</option>
                    <?php $__currentLoopData = $delegations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $del): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($del->id); ?>" <?php if($delegationId == $del->id): echo 'selected'; endif; ?>><?php echo e($del->title); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Statut</label>
                <select name="status" class="form-select">
                    <option value="">Tous</option>
                    <option value="active" <?php if($status === 'active'): echo 'selected'; endif; ?>>Actifs</option>
                    <option value="pending" <?php if($status === 'pending'): echo 'selected'; endif; ?>>En attente</option>
                    <option value="rejected" <?php if($status === 'rejected'): echo 'selected'; endif; ?>>Rejetés</option>
                    <option value="inactive" <?php if($status === 'inactive'): echo 'selected'; endif; ?>>Inactifs</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i> Filtrer
                    </button>
                    <?php if(collect([$search, $service, $delegationId, $status])->filter()->isNotEmpty()): ?>
                        <a href="<?php echo e(route('users.index')); ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</div>


<div class="modern-card" id="user-status-root">
    <div class="modern-card-header">
        <h5 class="modern-card-title">
            <i class="bi bi-list-ul"></i>
            Liste des utilisateurs
        </h5>
        <span class="badge-modern badge-modern-primary">
            <?php echo e($users->total()); ?> résultat<?php echo e($users->total() > 1 ? 's' : ''); ?>

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
                    <?php
                        $canAdminUsers = auth()->user()->hasAnyRole(['super-admin', 'admin', 'dsi'])
                            || auth()->user()->can('users.manage');
                    ?>
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr data-user-id="<?php echo e($user->id); ?>">
                            <td class="fw-semibold"><?php echo e($user->last_name ?: ($user->name ?: '—')); ?></td>
                            <td><?php echo e($user->first_name ?: '—'); ?></td>
                            <td>
                                <?php if($user->service): ?>
                                    <?php echo e($user->service); ?>

                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($user->delegation): ?>
                                    <?php echo e($user->delegation->title); ?>

                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="text-muted small"><?php echo e(Str::limit($user->email, 20)); ?></span>
                            </td>
                            <td>
                                <?php if($user->roles->isNotEmpty()): ?>
                                    <?php
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
                                    ?>
                                    <span class="badge <?php echo e($roleColor); ?>"><?php echo e($firstRole->name); ?></span>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
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
                                ?>
                                <span class="badge <?php echo e($statusClass); ?>"><?php echo e($statusLabel); ?></span>
                            </td>
                            <td class="text-end">
                                <div class="table-actions d-flex gap-1 justify-content-end">
                                    <a href="<?php echo e(route('users.show', $user)); ?>" 
                                       class="btn btn-sm btn-outline-primary"
                                       data-bs-toggle="tooltip"
                                       title="Voir les détails">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <?php if($canAdminUsers): ?>
                                        
                                        <?php if(($user->status ?? 'inactive') === 'pending'): ?>
                                            <form action="<?php echo e(route('users.approve', $user)); ?>"
                                                  method="POST"
                                                  class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit"
                                                        class="btn btn-sm btn-success"
                                                        data-bs-toggle="tooltip"
                                                        title="Valider et activer ce compte">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>

                                        <a href="<?php echo e(route('users.edit', $user)); ?>" 
                                           class="btn btn-sm btn-outline-secondary"
                                           data-bs-toggle="tooltip"
                                           title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    <?php endif; ?>

                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $user)): ?>
                                    <form action="<?php echo e(route('users.destroy', $user)); ?>" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="tooltip"
                                                title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                                <p class="text-muted mb-0">Aucun utilisateur trouvé.</p>
                                <?php if(collect([$search, $service, $delegationId, $status])->filter()->isNotEmpty()): ?>
                                    <a href="<?php echo e(route('users.index')); ?>" class="btn btn-sm btn-outline-primary mt-2">
                                        Réinitialiser les filtres
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($users->hasPages()): ?>
        <div class="modern-card-footer">
            <div class="small text-muted">
                Affichage de <?php echo e($users->firstItem()); ?> à <?php echo e($users->lastItem()); ?> 
                sur <?php echo e($users->total()); ?> utilisateur<?php echo e($users->total() > 1 ? 's' : ''); ?>

            </div>
            <div class="pagination-modern">
                <?php echo e($users->appends(request()->query())->links()); ?>

            </div>
        </div>
    <?php endif; ?>


<?php $__env->startPush('styles'); ?>
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
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Initialiser les tooltips Bootstrap (comportement léger et prévisible)
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>


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
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views/users/index.blade.php ENDPATH**/ ?>