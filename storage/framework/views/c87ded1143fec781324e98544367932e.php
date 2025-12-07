

<?php $__env->startSection('title', $meetingRequest->title); ?>

<?php $__env->startSection('content'); ?>
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Accueil</a></li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('meeting-requests.index')); ?>">Demandes de réunion</a></li>
        <li class="breadcrumb-item active"><?php echo e(Str::limit($meetingRequest->title, 30)); ?></li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold"><?php echo e($meetingRequest->title); ?></h3>
        <p class="text-muted mb-0 small">Accueil / Demandes de réunion / Détails</p>
    </div>
    <div>
        <?php
            $statusClass = match($meetingRequest->status) {
                'pending' => 'bg-warning text-dark',
                'approved' => 'bg-success',
                'rejected' => 'bg-danger',
                default => 'bg-secondary'
            };
        ?>
        <span class="badge <?php echo e($statusClass); ?> fs-6"><?php echo e(ucfirst($meetingRequest->status)); ?></span>
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
                    <dt class="col-sm-3">Titre</dt>
                    <dd class="col-sm-9"><?php echo e($meetingRequest->title); ?></dd>
                    
                    <dt class="col-sm-3">Description</dt>
                    <dd class="col-sm-9"><?php echo e($meetingRequest->description ?? 'Aucune description'); ?></dd>
                    
                    <dt class="col-sm-3">Type</dt>
                    <dd class="col-sm-9"><?php echo e($meetingRequest->meetingType->name ?? '—'); ?></dd>
                    
                    <dt class="col-sm-3">Comité</dt>
                    <dd class="col-sm-9"><?php echo e($meetingRequest->committee->name ?? '—'); ?></dd>
                    
                    <dt class="col-sm-3">Date demandée</dt>
                    <dd class="col-sm-9"><?php echo e($meetingRequest->requested_start_at->format('d/m/Y H:i')); ?></dd>
                    
                    <dt class="col-sm-3">Salle demandée</dt>
                    <dd class="col-sm-9"><?php echo e($meetingRequest->requestedRoom->name ?? ($meetingRequest->other_location ?? '—')); ?></dd>
                    
                    <dt class="col-sm-3">Justification</dt>
                    <dd class="col-sm-9"><?php echo e($meetingRequest->justification ?? '—'); ?></dd>
                    
                    <dt class="col-sm-3">Demandeur</dt>
                    <dd class="col-sm-9"><?php echo e($meetingRequest->requester->name); ?></dd>
                    
                    <?php if($meetingRequest->reviewed_by): ?>
                        <dt class="col-sm-3">Examiné par</dt>
                        <dd class="col-sm-9"><?php echo e($meetingRequest->reviewer->name); ?></dd>
                        
                        <dt class="col-sm-3">Date d'examen</dt>
                        <dd class="col-sm-9"><?php echo e($meetingRequest->reviewed_at->format('d/m/Y H:i')); ?></dd>
                        
                        <dt class="col-sm-3">Commentaires</dt>
                        <dd class="col-sm-9"><?php echo e($meetingRequest->review_comments ?? '—'); ?></dd>
                    <?php endif; ?>
                    
                    <?php if($meetingRequest->meeting): ?>
                        <dt class="col-sm-3">Réunion créée</dt>
                        <dd class="col-sm-9">
                            <a href="<?php echo e(route('meetings.show', $meetingRequest->meeting)); ?>">
                                <?php echo e($meetingRequest->meeting->title); ?>

                            </a>
                        </dd>
                    <?php endif; ?>
                </dl>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approve', $meetingRequest)): ?>
            <?php if($meetingRequest->status === 'pending'): ?>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">Actions</h6>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo e(route('meeting-requests.approve', $meetingRequest)); ?>" method="POST" class="mb-2">
                            <?php echo csrf_field(); ?>
                            <div class="mb-2">
                                <label class="form-label small">Commentaires (optionnel)</label>
                                <textarea name="review_comments" class="form-control form-control-sm" rows="2"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-check-circle me-1"></i> Approuver
                            </button>
                        </form>
                        
                        <form action="<?php echo e(route('meeting-requests.reject', $meetingRequest)); ?>" method="POST">
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


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\meeting-requests\show.blade.php ENDPATH**/ ?>