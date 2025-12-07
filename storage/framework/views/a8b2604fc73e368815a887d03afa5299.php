<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Nouvelle délégation</h4>
        <div class="small">
            <a href="<?php echo e(route('dashboard')); ?>" class="text-decoration-none text-muted">Accueil</a>
            <span class="text-muted">/</span>
            <a href="<?php echo e(route('delegations.index')); ?>" class="text-decoration-none text-muted">Délégations</a>
            <span class="text-muted">/</span>
            <span class="text-muted">Nouvelle</span>
        </div>
        <p class="text-muted mb-0 mt-1">Créez une nouvelle délégation pour les États membres de la CEEAC.</p>
    </div>
    <a href="<?php echo e(route('delegations.index')); ?>" class="btn btn-outline-secondary">
        Retour à la liste
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <?php echo $__env->make('partials.alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        
        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <h6 class="alert-heading">
                    <i class="bi bi-exclamation-triangle"></i> Erreurs de validation
                </h6>
                <ul class="mb-0">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('delegations.store')); ?>">
            <?php if(request('meeting_id')): ?>
                <input type="hidden" name="redirect_to_meeting" value="1">
            <?php endif; ?>
            <?php echo $__env->make('delegations._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="<?php echo e(route('delegations.index')); ?>" class="btn btn-outline-secondary" id="cancelBtn">
                    <i class="bi bi-x-circle"></i> Annuler
                </a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="bi bi-check-circle"></i> Enregistrer la délégation
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\delegations\create.blade.php ENDPATH**/ ?>