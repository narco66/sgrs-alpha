

<?php $__env->startSection('title', 'Détails de la demande'); ?>

<?php $__env->startSection('content'); ?>
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Accueil</a></li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('participant-requests.index')); ?>">Demandes de participants</a></li>
        <li class="breadcrumb-item active">Détails</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold">Demande d'ajout de participant</h3>
        <p class="text-muted mb-0 small">Accueil / Demandes de participants / Détails</p>
    </div>
    <div>
        <?php
            $statusClass = match($participantRequest->status) {
                'pending' => 'bg-warning text-dark',
                'approved' => 'bg-success',
                'rejected' => 'bg-danger',
                default => 'bg-secondary'
            };
        ?>
        <span class="badge <?php echo e($statusClass); ?> fs-6"><?php echo e(ucfirst($participantRequest->status)); ?></span>
    </div>
</div>

<?php echo $__env->make('partials.alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Détails de la demande</h5>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-3">Participant</dt>
                    <dd class="col-sm-9">
                        <strong><?php echo e($participantRequest->participant_name); ?></strong>
                        <?php if($participantRequest->participant_email): ?>
                            <br><small class="text-muted"><?php echo e($participantRequest->participant_email); ?></small>
                        <?php endif; ?>
                    </dd>
                    
                    <dt class="col-sm-3">Rôle</dt>
                    <dd class="col-sm-9"><?php echo e($participantRequest->participant_role ?? '—'); ?></dd>
                    
                    <dt class="col-sm-3">Réunion</dt>
                    <dd class="col-sm-9">
                        <a href="<?php echo e(route('meetings.show', $participantRequest->meeting)); ?>">
                            <?php echo e($participantRequest->meeting->title); ?>

                        </a>
                    </dd>
                    
                    <dt class="col-sm-3">Justification</dt>
                    <dd class="col-sm-9"><?php echo e($participantRequest->justification); ?></dd>
                    
                    <dt class="col-sm-3">Demandeur</dt>
                    <dd class="col-sm-9"><?php echo e($participantRequest->requester->name); ?></dd>
                    
                    <?php if($participantRequest->reviewed_by): ?>
                        <dt class="col-sm-3">Examiné par</dt>
                        <dd class="col-sm-9"><?php echo e($participantRequest->reviewer->name); ?></dd>
                        
                        <dt class="col-sm-3">Date d'examen</dt>
                        <dd class="col-sm-9"><?php echo e($participantRequest->reviewed_at->format('d/m/Y H:i')); ?></dd>
                        
                        <dt class="col-sm-3">Commentaires</dt>
                        <dd class="col-sm-9"><?php echo e($participantRequest->review_comments ?? '—'); ?></dd>
                    <?php endif; ?>
                    
                    <?php if($participantRequest->participant): ?>
                        <dt class="col-sm-3">Participant créé</dt>
                        <dd class="col-sm-9">
                            <span class="badge bg-success">Participant ajouté avec succès</span>
                        </dd>
                    <?php endif; ?>
                </dl>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approve', $participantRequest)): ?>
            <?php if($participantRequest->status === 'pending'): ?>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">Actions</h6>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo e(route('participant-requests.approve', $participantRequest)); ?>" method="POST" class="mb-2">
                            <?php echo csrf_field(); ?>
                            <div class="mb-2">
                                <label class="form-label small">Commentaires (optionnel)</label>
                                <textarea name="review_comments" class="form-control form-control-sm" rows="2"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-check-circle me-1"></i> Approuver
                            </button>
                        </form>
                        
                        <form action="<?php echo e(route('participant-requests.reject', $participantRequest)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="mb-2">
                                <label class="form-label small">Motif du rejet <span class="text-danger">*</span></label>
                                <textarea name="review_comments" class="form-control form-control-sm" rows="2" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-x-circle me-1"></i> Rejeter
                            </button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\participant-requests\show.blade.php ENDPATH**/ ?>