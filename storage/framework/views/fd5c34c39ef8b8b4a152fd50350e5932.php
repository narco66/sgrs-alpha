<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1"><?php echo e($participant->full_name); ?></h4>
        <div class="small">
            <a href="<?php echo e(route('dashboard')); ?>" class="text-decoration-none text-muted">Accueil</a>
            <span class="text-muted">/</span>
            <a href="<?php echo e(route('participants.index')); ?>" class="text-decoration-none text-muted">Participants</a>
            <span class="text-muted">/</span>
            <span class="text-muted"><?php echo e($participant->full_name); ?></span>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('participants.edit', $participant)); ?>" class="btn btn-outline-primary">
            <i class="bi bi-pencil me-1"></i> Modifier
        </a>
        <a href="<?php echo e(route('participants.index')); ?>" class="btn btn-outline-secondary">
            Retour
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h6 class="mb-0">Informations</h6>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-md-4">Nom complet</dt>
                    <dd class="col-md-8"><?php echo e($participant->full_name); ?></dd>

                    <dt class="col-md-4">Email</dt>
                    <dd class="col-md-8">
                        <?php if($participant->email): ?>
                            <a href="mailto:<?php echo e($participant->email); ?>"><?php echo e($participant->email); ?></a>
                        <?php else: ?>
                            <span class="text-muted">Non renseigné</span>
                        <?php endif; ?>
                    </dd>

                    <dt class="col-md-4">Téléphone</dt>
                    <dd class="col-md-8"><?php echo e($participant->phone ?? 'Non renseigné'); ?></dd>

                    <dt class="col-md-4">Fonction</dt>
                    <dd class="col-md-8"><?php echo e($participant->position ?? 'Non renseignée'); ?></dd>

                    <dt class="col-md-4">Institution</dt>
                    <dd class="col-md-8"><?php echo e($participant->institution ?? 'Non renseignée'); ?></dd>

                    <dt class="col-md-4">Pays</dt>
                    <dd class="col-md-8"><?php echo e($participant->country ?? 'Non renseigné'); ?></dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h6 class="mb-0">Statut</h6>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <?php if($participant->is_internal): ?>
                        <span class="badge-modern badge-modern-primary">Interne</span>
                    <?php else: ?>
                        <span class="badge-modern badge-modern-info">Externe</span>
                    <?php endif; ?>
                </p>
                <p class="mb-3">
                    <?php if($participant->is_active): ?>
                        <span class="badge-modern badge-modern-success">Actif</span>
                    <?php else: ?>
                        <span class="badge-modern badge-modern-secondary">Inactif</span>
                    <?php endif; ?>
                </p>
                <p class="text-muted small mb-0">
                    Créé le <?php echo e($participant->created_at?->format('d/m/Y H:i') ?? 'N/A'); ?><br>
                    Mis à jour le <?php echo e($participant->updated_at?->format('d/m/Y H:i') ?? 'N/A'); ?>

                </p>
            </div>
        </div>

        <div class="card shadow-sm border-0 mt-3">
            <div class="card-header bg-white">
                <h6 class="mb-0">Réunions associées</h6>
            </div>
            <div class="card-body">
                <?php $meetings = $participant->meetings()->latest('start_at')->take(5)->get(); ?>
                <?php $__empty_1 = true; $__currentLoopData = $meetings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meeting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="mb-2">
                        <a href="<?php echo e(route('meetings.show', $meeting)); ?>" class="fw-semibold text-decoration-none">
                            <?php echo e($meeting->title); ?>

                        </a>
                        <div class="text-muted small">
                            <?php echo e($meeting->start_at ? \Carbon\Carbon::parse($meeting->start_at)->format('d/m/Y H:i') : 'Date à confirmer'); ?>

                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-muted mb-0">Aucune réunion liée.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\participants\show.blade.php ENDPATH**/ ?>