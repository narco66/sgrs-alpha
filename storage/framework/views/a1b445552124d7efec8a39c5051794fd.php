<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-center">
    <div class="card shadow-lg border-0" style="max-width: 800px; width: 100%;">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Nouveau participant</h5>
            <a href="<?php echo e(route('participants.index')); ?>" class="btn btn-sm btn-outline-secondary">
                Retour
            </a>
        </div>
        <div class="card-body">
            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('participants.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nom</label>
                        <input type="text" name="last_name" class="form-control"
                               value="<?php echo e(old('last_name')); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Prénom</label>
                        <input type="text" name="first_name" class="form-control"
                               value="<?php echo e(old('first_name')); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Adresse e-mail</label>
                        <input type="email" name="email" class="form-control"
                               value="<?php echo e(old('email')); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="phone" class="form-control"
                               value="<?php echo e(old('phone')); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fonction</label>
                        <input type="text" name="position" class="form-control"
                               value="<?php echo e(old('position')); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Institution / Ministère</label>
                        <input type="text" name="institution" class="form-control"
                               value="<?php echo e(old('institution')); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Pays</label>
                        <input type="text" name="country" class="form-control"
                               value="<?php echo e(old('country')); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Type</label>
                        <select name="is_internal" class="form-select">
                            <option value="1" <?php if(old('is_internal', 1) == 1): echo 'selected'; endif; ?>>Interne CEEAC</option>
                            <option value="0" <?php if(old('is_internal') === '0'): echo 'selected'; endif; ?>>Externe</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Statut</label>
                        <select name="is_active" class="form-select">
                            <option value="1" <?php if(old('is_active', 1) == 1): echo 'selected'; endif; ?>>Actif</option>
                            <option value="0" <?php if(old('is_active') === '0'): echo 'selected'; endif; ?>>Inactif</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="<?php echo e(route('participants.index')); ?>" class="btn btn-outline-secondary">
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\participants\create.blade.php ENDPATH**/ ?>