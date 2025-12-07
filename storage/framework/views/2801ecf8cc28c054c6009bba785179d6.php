<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Modifier le type de réunion</h4>
        <p class="text-muted mb-0">
            <?php echo e($meetingType->name); ?> (<?php echo e($meetingType->code); ?>)
        </p>
    </div>
    <a href="<?php echo e(route('meeting-types.index')); ?>" class="btn btn-outline-secondary">
        Retour à la liste
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <?php echo $__env->make('partials.alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <form method="POST" action="<?php echo e(route('meeting-types.update', $meetingType)); ?>">
            <?php echo method_field('PUT'); ?>
            <?php echo $__env->make('meeting_types._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="<?php echo e(route('meeting-types.index')); ?>" class="btn btn-outline-secondary">
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\meeting_types\edit.blade.php ENDPATH**/ ?>