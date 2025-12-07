

<?php $__env->startSection('title', 'Modifier le Rôle'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Accueil</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('roles.index')); ?>">Rôles</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('roles.show', $role)); ?>"><?php echo e($role->name); ?></a></li>
            <li class="breadcrumb-item active">Modifier</li>
        </ol>
    </nav>

    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title">
                <i class="bi bi-pencil text-warning"></i>
                Modifier le Rôle : <?php echo e($role->name); ?>

            </h2>
        </div>
        <a href="<?php echo e(route('roles.show', $role)); ?>" class="btn btn-modern btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    <?php echo $__env->make('partials.alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="modern-card">
        <div class="modern-card-body">
            <form action="<?php echo e(route('roles.update', $role)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                
                <?php
                    $systemRoles = ['super-admin', 'admin', 'sg', 'dsi', 'staff'];
                    $isSystem = in_array($role->name, $systemRoles);
                ?>
                <div class="form-group mb-4">
                    <label class="form-label">
                        <i class="bi bi-tag"></i>
                        Nom du rôle
                        <span class="text-danger">*</span>
                        <?php if($isSystem): ?>
                            <span class="badge bg-warning text-dark ms-2">
                                <i class="bi bi-lock-fill"></i> Rôle Système
                            </span>
                        <?php endif; ?>
                    </label>
                    <?php if($isSystem): ?>
                        <input type="text" 
                               class="form-control" 
                               value="<?php echo e($role->name); ?>" 
                               disabled
                               title="Le nom des rôles système ne peut pas être modifié">
                        <input type="hidden" name="name" value="<?php echo e($role->name); ?>">
                        <small class="form-text text-muted">
                            <i class="bi bi-info-circle"></i> Le nom des rôles système ne peut pas être modifié.
                        </small>
                    <?php else: ?>
                        <input type="text" 
                               name="name" 
                               class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               value="<?php echo e(old('name', $role->name)); ?>" 
                               required>
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <?php endif; ?>
                </div>

                
                <div class="form-group mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <label class="form-label mb-0">
                            <i class="bi bi-key"></i>
                            Permissions
                        </label>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="select-all-permissions">
                                <i class="bi bi-check-all"></i> Tout sélectionner
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="deselect-all-permissions">
                                <i class="bi bi-x-circle"></i> Tout désélectionner
                            </button>
                        </div>
                    </div>
                    <div class="permissions-container">
                        <?php
                            $rolePermissionIds = $role->permissions->pluck('id')->toArray();
                        ?>
                        <?php $__currentLoopData = $allPermissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module => $permissions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="permission-module mb-3">
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <div class="form-check">
                                            <input class="form-check-input module-checkbox" 
                                                   type="checkbox" 
                                                   id="module-<?php echo e($module); ?>"
                                                   data-module="<?php echo e($module); ?>">
                                            <label class="form-check-label fw-bold" for="module-<?php echo e($module); ?>">
                                                <?php echo e(ucfirst(str_replace('_', ' ', $module))); ?>

                                            </label>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-2">
                                            <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="col-md-6 col-lg-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input permission-checkbox" 
                                                               type="checkbox" 
                                                               name="permissions[]" 
                                                               value="<?php echo e($permission->id); ?>"
                                                               id="permission-<?php echo e($permission->id); ?>"
                                                               data-module="<?php echo e($module); ?>"
                                                               <?php echo e(in_array($permission->id, $rolePermissionIds) ? 'checked' : ''); ?>>
                                                        <label class="form-check-label" for="permission-<?php echo e($permission->id); ?>">
                                                            <?php echo e($permission->name); ?>

                                                        </label>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                
                <div class="d-flex justify-content-between align-items-center mt-4 pt-4 border-top">
                    <a href="<?php echo e(route('roles.show', $role)); ?>" class="btn btn-modern btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Retour
                    </a>
                    <div class="d-flex align-items-center gap-3">
                        <span class="text-muted" id="selected-count">
                            <i class="bi bi-check-circle"></i> 
                            <span id="count">0</span> permission(s) sélectionnée(s)
                        </span>
                        <button type="submit" class="btn btn-modern btn-primary">
                            <i class="bi bi-check-circle"></i> Enregistrer les Modifications
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
    .permissions-container {
        max-height: 600px;
        overflow-y: auto;
        padding: 10px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        background-color: #f8f9fa;
    }
    
    .permission-module .card {
        margin-bottom: 15px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .permission-module .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 600;
        padding: 12px 15px;
    }
    
    .permission-module .card-header .form-check-label {
        color: white;
        cursor: pointer;
    }
    
    .permission-module .card-body {
        padding: 15px;
    }
    
    .permission-checkbox {
        margin-right: 8px;
    }
    
    .form-check-label {
        cursor: pointer;
        user-select: none;
    }
    
    .form-check:hover {
        background-color: #f0f0f0;
        padding: 5px;
        border-radius: 4px;
        transition: background-color 0.2s;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Initialiser l'état des checkboxes module
    document.querySelectorAll('.permission-module').forEach(module => {
        const moduleName = module.querySelector('.module-checkbox').dataset.module;
        const moduleCheckbox = document.querySelector(`#module-${moduleName}`);
        const modulePermissions = module.querySelectorAll('.permission-checkbox');
        const checkedCount = Array.from(modulePermissions).filter(p => p.checked).length;
        
        if (checkedCount === 0) {
            moduleCheckbox.checked = false;
            moduleCheckbox.indeterminate = false;
        } else if (checkedCount === modulePermissions.length) {
            moduleCheckbox.checked = true;
            moduleCheckbox.indeterminate = false;
        } else {
            moduleCheckbox.checked = false;
            moduleCheckbox.indeterminate = true;
        }
    });

    // Sélection/désélection par module
    document.querySelectorAll('.module-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const module = this.dataset.module;
            const modulePermissions = document.querySelectorAll(
                `.permission-checkbox[data-module="${module}"]`
            );
            modulePermissions.forEach(perm => {
                perm.checked = this.checked;
            });
            this.indeterminate = false;
        });
    });

    // Mise à jour de l'état du checkbox module
    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const module = this.dataset.module;
            const moduleCheckbox = document.querySelector(`#module-${module}`);
            const modulePermissions = document.querySelectorAll(
                `.permission-checkbox[data-module="${module}"]`
            );
            const checkedCount = Array.from(modulePermissions).filter(p => p.checked).length;
            
            if (checkedCount === 0) {
                moduleCheckbox.checked = false;
                moduleCheckbox.indeterminate = false;
            } else if (checkedCount === modulePermissions.length) {
                moduleCheckbox.checked = true;
                moduleCheckbox.indeterminate = false;
            } else {
                moduleCheckbox.checked = false;
                moduleCheckbox.indeterminate = true;
            }
        });
    });

    // Sélectionner toutes les permissions
    document.getElementById('select-all-permissions')?.addEventListener('click', function() {
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.checked = true;
        });
        document.querySelectorAll('.module-checkbox').forEach(checkbox => {
            checkbox.checked = true;
            checkbox.indeterminate = false;
        });
        updateSelectedCount();
    });

    // Désélectionner toutes les permissions
    document.getElementById('deselect-all-permissions')?.addEventListener('click', function() {
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
        document.querySelectorAll('.module-checkbox').forEach(checkbox => {
            checkbox.checked = false;
            checkbox.indeterminate = false;
        });
        updateSelectedCount();
    });

    // Fonction pour mettre à jour le compteur de permissions sélectionnées
    function updateSelectedCount() {
        const checked = document.querySelectorAll('.permission-checkbox:checked').length;
        const total = document.querySelectorAll('.permission-checkbox').length;
        const countElement = document.getElementById('count');
        if (countElement) {
            countElement.textContent = checked;
            const selectedCount = document.getElementById('selected-count');
            if (selectedCount) {
                if (checked === total) {
                    selectedCount.innerHTML = '<i class="bi bi-check-all text-success"></i> Toutes les permissions sélectionnées (' + checked + '/' + total + ')';
                } else if (checked === 0) {
                    selectedCount.innerHTML = '<i class="bi bi-x-circle text-danger"></i> Aucune permission sélectionnée';
                } else {
                    selectedCount.innerHTML = '<i class="bi bi-check-circle text-primary"></i> ' + checked + ' permission(s) sélectionnée(s) sur ' + total;
                }
            }
        }
    }

    // Mettre à jour le compteur au chargement
    updateSelectedCount();

    // Mettre à jour le compteur lors des changements
    document.querySelectorAll('.permission-checkbox, .module-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\roles\edit.blade.php ENDPATH**/ ?>