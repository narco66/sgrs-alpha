

<?php $__env->startSection('title', 'Détails du Rôle'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Accueil</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('roles.index')); ?>">Rôles</a></li>
            <li class="breadcrumb-item active"><?php echo e($role->name); ?></li>
        </ol>
    </nav>

    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title">
                <i class="bi bi-shield-check text-primary"></i>
                Détails du Rôle : <?php echo e($role->name); ?>

            </h2>
            <?php
                $systemRoles = ['super-admin', 'admin', 'sg', 'dsi', 'staff'];
                $isSystem = in_array($role->name, $systemRoles);
            ?>
            <?php if($isSystem): ?>
                <span class="badge bg-warning text-dark mt-2">
                    <i class="bi bi-lock-fill"></i> Rôle Système
                </span>
            <?php else: ?>
                <span class="badge bg-success mt-2">
                    <i class="bi bi-unlock-fill"></i> Rôle Personnalisé
                </span>
            <?php endif; ?>
        </div>
        <div class="btn-group">
            <?php if(auth()->user() && auth()->user()->hasRole('super-admin')): ?>
                <a href="<?php echo e(route('roles.edit', $role)); ?>" class="btn btn-modern btn-warning">
                    <i class="bi bi-pencil"></i> Modifier les Permissions
                </a>
            <?php endif; ?>
            <a href="<?php echo e(route('roles.index')); ?>" class="btn btn-modern btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <?php echo $__env->make('partials.alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="row g-4">
        
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
                        <p class="mb-0 fw-bold"><?php echo e($role->name); ?></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Nombre de permissions</label>
                        <p class="mb-0">
                            <span class="badge bg-info">
                                <?php echo e($role->permissions->count()); ?> permission(s)
                            </span>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Nombre d'utilisateurs</label>
                        <p class="mb-0">
                            <span class="badge bg-secondary">
                                <?php echo e($role->users->count()); ?> utilisateur(s)
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="form-label text-muted small">Date de création</label>
                        <p class="mb-0"><?php echo e($role->created_at->format('d/m/Y à H:i')); ?></p>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-md-8">
            <div class="modern-card">
                <div class="modern-card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-key"></i>
                        Permissions (<?php echo e($role->permissions->count()); ?>)
                    </h5>
                    <?php if(auth()->user() && auth()->user()->hasRole('super-admin')): ?>
                        <a href="<?php echo e(route('roles.edit', $role)); ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i> Modifier les Permissions
                        </a>
                    <?php endif; ?>
                </div>
                <div class="modern-card-body">
                    <?php if($role->permissions->count() > 0): ?>
                        <div class="permissions-list">
                            <?php $__currentLoopData = $allPermissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module => $permissions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $modulePermissions = $permissions->filter(function($p) use ($role) {
                                        return $role->permissions->contains($p);
                                    });
                                ?>
                                <?php if($modulePermissions->count() > 0): ?>
                                    <div class="permission-module mb-3">
                                        <div class="card border">
                                            <div class="card-header bg-light">
                                                <strong><?php echo e(ucfirst(str_replace('_', ' ', $module))); ?></strong>
                                                <span class="badge bg-primary ms-2"><?php echo e($modulePermissions->count()); ?></span>
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-2">
                                                    <?php $__currentLoopData = $modulePermissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="col-md-6">
                                                            <span class="badge bg-success">
                                                                <i class="bi bi-check-circle"></i>
                                                                <?php echo e($permission->name); ?>

                                                            </span>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="bi bi-shield-x display-4 text-muted"></i>
                            <p class="text-muted mt-3">Aucune permission attribuée</p>
                            <?php if(auth()->user() && auth()->user()->hasRole('super-admin')): ?>
                                <a href="<?php echo e(route('roles.edit', $role)); ?>" class="btn btn-modern btn-primary">
                                    <i class="bi bi-plus-circle"></i> Ajouter des permissions
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <?php if($role->users->count() > 0): ?>
        <div class="modern-card mt-4">
            <div class="modern-card-header">
                <h5 class="mb-0">
                    <i class="bi bi-people"></i>
                    Utilisateurs avec ce rôle (<?php echo e($role->users->count()); ?>)
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
                            <?php $__currentLoopData = $role->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo e(route('users.show', $user)); ?>">
                                            <?php echo e($user->name); ?>

                                        </a>
                                    </td>
                                    <td><?php echo e($user->email); ?></td>
                                    <td>
                                        <?php if($user->delegation): ?>
                                            <span class="badge bg-info"><?php echo e($user->delegation->name); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($user->is_active): ?>
                                            <span class="badge bg-success">Actif</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inactif</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\roles\show.blade.php ENDPATH**/ ?>