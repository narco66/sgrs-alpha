

<?php $__env->startSection('title', 'Créer un Rôle'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Accueil</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('roles.index')); ?>">Rôles</a></li>
            <li class="breadcrumb-item active">Créer</li>
        </ol>
    </nav>

    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title">
                <i class="bi bi-plus-circle text-primary"></i>
                Créer un Nouveau Rôle
            </h2>
        </div>
        <a href="<?php echo e(route('roles.index')); ?>" class="btn btn-modern btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    <?php echo $__env->make('partials.alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="modern-card">
        <div class="modern-card-body">
            <form action="<?php echo e(route('roles.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                
                <div class="form-group mb-4">
                    <label class="form-label">
                        <i class="bi bi-tag"></i>
                        Nom du rôle
                        <span class="text-danger">*</span>
                    </label>
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
                           value="<?php echo e(old('name')); ?>" 
                           placeholder="Ex: organisateur, moderateur, etc."
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
                    <small class="form-text text-muted">
                        Le nom doit être unique et en minuscules (ex: organisateur-reunion)
                    </small>
                </div>

                
                <div class="form-group mb-4">
                    <label class="form-label">
                        <i class="bi bi-key"></i>
                        Permissions
                    </label>
                    <div class="permissions-container">
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
                                                               data-module="<?php echo e($module); ?>">
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

                
                <div class="d-flex justify-content-between">
                    <a href="<?php echo e(route('roles.index')); ?>" class="btn btn-modern btn-outline-secondary">
                        <i class="bi bi-x-circle"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-modern btn-primary">
                        <i class="bi bi-check-circle"></i> Créer le Rôle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
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
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\roles\create.blade.php ENDPATH**/ ?>