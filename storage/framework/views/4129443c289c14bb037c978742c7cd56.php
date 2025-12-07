<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Modifier la délégation</h4>
        <div class="small">
            <a href="<?php echo e(route('dashboard')); ?>" class="text-decoration-none text-muted">Accueil</a>
            <span class="text-muted">/</span>
            <a href="<?php echo e(route('delegations.index')); ?>" class="text-decoration-none text-muted">Délégations</a>
            <span class="text-muted">/</span>
            <a href="<?php echo e(route('delegations.show', $delegation)); ?>" class="text-decoration-none text-muted"><?php echo e($delegation->title); ?></a>
            <span class="text-muted">/</span>
            <span class="text-muted">Édition</span>
        </div>
        <p class="text-muted mb-0 mt-1"><?php echo e($delegation->title); ?></p>
    </div>
    <a href="<?php echo e(route('delegations.index')); ?>" class="btn btn-outline-secondary">
        Retour à la liste
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <?php echo $__env->make('partials.alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <form method="POST" action="<?php echo e(route('delegations.update', $delegation)); ?>">
            <?php echo method_field('PUT'); ?>
            <?php if(request('redirect_to_meeting') || ($delegation->meeting_id && request('from_meeting'))): ?>
                <input type="hidden" name="redirect_to_meeting" value="1">
            <?php endif; ?>
            <?php echo $__env->make('delegations._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="<?php echo e(route('delegations.index')); ?>" class="btn btn-outline-secondary">
                    Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\delegations\edit.blade.php ENDPATH**/ ?>