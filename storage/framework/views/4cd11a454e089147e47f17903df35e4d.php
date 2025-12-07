<?php $__env->startSection('title', 'Gestion des Rôles'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
<div>
            <h2 class="page-title">
                <i class="bi bi-shield-check text-primary"></i>
                Gestion des Rôles et Permissions
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Accueil</a></li>
                    <li class="breadcrumb-item active">Rôles</li>
                </ol>
            </nav>
        </div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \Spatie\Permission\Models\Role::class)): ?>
            <a href="<?php echo e(route('roles.create')); ?>" class="btn btn-modern btn-primary">
                <i class="bi bi-plus-circle"></i> Nouveau Rôle
            </a>
        <?php endif; ?>
    </div>

    
    <div class="modern-card mb-4">
        <div class="modern-card-body">
            <form method="GET" action="<?php echo e(route('roles.index')); ?>" class="row g-3">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="form-label">Rechercher un rôle</label>
                        <input type="text" name="search" class="form-control" 
                               value="<?php echo e($search); ?>" 
                               placeholder="Nom du rôle...">
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-modern btn-primary me-2">
                        <i class="bi bi-search"></i> Rechercher
                    </button>
                    <?php if($search): ?>
                        <a href="<?php echo e(route('roles.index')); ?>" class="btn btn-modern btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Réinitialiser
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    
    <div class="modern-card">
        <div class="modern-card-header">
            <h5 class="mb-0">
                <i class="bi bi-list-ul"></i>
                Liste des Rôles (<?php echo e($roles->total()); ?>)
            </h5>
        </div>
        <div class="modern-card-body p-0">
            <?php if($roles->count() > 0): ?>
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
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-shield-check text-primary me-2"></i>
                                            <strong><?php echo e($role->name); ?></strong>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?php echo e($role->permissions_count ?? $role->permissions->count()); ?> permission(s)
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <?php echo e($role->users_count ?? $role->users->count()); ?> utilisateur(s)
                                        </span>
                                    </td>
                                    <td>
                                        <?php
                                            $systemRoles = ['super-admin', 'admin', 'sg', 'dsi', 'staff'];
                                            $isSystem = in_array($role->name, $systemRoles);
                                        ?>
                                        <?php if($isSystem): ?>
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-lock-fill"></i> Système
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-success">
                                                <i class="bi bi-unlock-fill"></i> Personnalisé
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <?php
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
                                        ?>
                                        <div class="btn-group" role="group">
                                            <?php if($canEdit): ?>
                                                <a href="<?php echo e(route('roles.show', $role)); ?>" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   title="Voir les détails et gérer les permissions">
                                                    <i class="bi bi-eye"></i> Voir
                                                </a>
                                                <a href="<?php echo e(route('roles.edit', $role)); ?>" 
                                                   class="btn btn-sm btn-outline-warning" 
                                                   title="Modifier les permissions">
                                                    <i class="bi bi-pencil"></i> Modifier
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted small">
                                                    <i class="bi bi-lock"></i> Réservé au Super-Admin
                                                </span>
                                            <?php endif; ?>
                                            <?php if($canDelete): ?>
                                                <form action="<?php echo e(route('roles.destroy', $role)); ?>" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce rôle ? Cette action est irréversible.');">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-danger" 
                                                            title="Supprimer">
                                                        <i class="bi bi-trash"></i> Supprimer
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                
                <?php if($roles->hasPages()): ?>
                    <div class="modern-card-footer">
                        <div class="small text-muted">
                            Affichage de <?php echo e($roles->firstItem()); ?> à <?php echo e($roles->lastItem()); ?> 
                            sur <?php echo e($roles->total()); ?> rôle(s)
                        </div>
                        <div class="pagination-modern">
                            <?php echo e($roles->appends(request()->query())->links()); ?>

                        </div>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="empty-state text-center py-5">
                    <i class="bi bi-shield-x display-1 text-muted"></i>
                    <h5 class="mt-3">Aucun rôle trouvé</h5>
                    <p class="text-muted">Commencez par créer un nouveau rôle.</p>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \Spatie\Permission\Models\Role::class)): ?>
                        <a href="<?php echo e(route('roles.create')); ?>" class="btn btn-modern btn-primary mt-3">
                            <i class="bi bi-plus-circle"></i> Créer un rôle
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\roles\index.blade.php ENDPATH**/ ?>