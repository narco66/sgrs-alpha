<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1"><?php echo e($committee->name); ?></h4>
        <p class="text-muted mb-0">
            Comité • Code : <?php echo e($committee->code); ?>

        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('committees.edit', $committee)); ?>" class="btn btn-outline-secondary">
            <i class="bi bi-pencil me-1"></i> Modifier
        </a>
        <a href="<?php echo e(route('committees.index')); ?>" class="btn btn-outline-secondary">
            Retour
        </a>
    </div>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-md-3">Code</dt>
            <dd class="col-md-9">
                <span class="badge bg-light text-dark border"><?php echo e($committee->code); ?></span>
            </dd>

            <dt class="col-md-3">Type de réunion associé</dt>
            <dd class="col-md-9">
                <?php if($committee->meetingType): ?>
                    <?php echo e($committee->meetingType->name); ?> (<?php echo e($committee->meetingType->code); ?>)
                <?php else: ?>
                    <span class="text-muted">Non défini</span>
                <?php endif; ?>
            </dd>

            <dt class="col-md-3">Nature</dt>
            <dd class="col-md-9">
                <?php if($committee->is_permanent): ?>
                    <span class="badge bg-info-subtle text-info-emphasis">
                        Permanent
                    </span>
                <?php else: ?>
                    <span class="badge bg-secondary-subtle text-secondary-emphasis">
                        Ad hoc
                    </span>
                <?php endif; ?>
            </dd>

            <dt class="col-md-3">Statut</dt>
            <dd class="col-md-9">
                <?php if($committee->is_active): ?>
                    <span class="badge bg-success-subtle text-success-emphasis">
                        Actif
                    </span>
                <?php else: ?>
                    <span class="badge bg-secondary-subtle text-secondary-emphasis">
                        Inactif
                    </span>
                <?php endif; ?>
            </dd>

            <dt class="col-md-3">Ordre d'affichage</dt>
            <dd class="col-md-9">
                <?php echo e($committee->sort_order); ?>

            </dd>

            <dt class="col-md-3">Description</dt>
            <dd class="col-md-9">
                <?php echo e($committee->description ?: '—'); ?>

            </dd>
        </dl>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\committees\show.blade.php ENDPATH**/ ?>