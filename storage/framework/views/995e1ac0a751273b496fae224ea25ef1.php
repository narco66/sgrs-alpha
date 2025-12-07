

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1"><?php echo e($delegation->title); ?></h4>
        <div class="small">
            <a href="<?php echo e(route('dashboard')); ?>" class="text-decoration-none text-muted">Accueil</a>
            <span class="text-muted">/</span>
            <a href="<?php echo e(route('delegations.index')); ?>" class="text-decoration-none text-muted">Délégations</a>
        </div>
        <p class="text-muted mb-0 mt-1">
            Délégation
            <?php if($delegation->code): ?>
                • Code : <?php echo e($delegation->code); ?>

            <?php endif; ?>
            <?php if($delegation->country): ?>
                • <?php echo e($delegation->country); ?>

            <?php endif; ?>
        </p>
    </div>
    <div class="d-flex gap-2">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $delegation)): ?>
        <a href="<?php echo e(route('delegations.edit', $delegation)); ?>" class="btn btn-outline-secondary">
            <i class="bi bi-pencil me-1"></i> Modifier
        </a>
        <?php endif; ?>
        <a href="<?php echo e(route('delegations.pdf', $delegation)); ?>" class="btn btn-outline-primary">
            <i class="bi bi-file-earmark-pdf"></i> PDF
        </a>
        <a href="<?php echo e(route('delegations.index')); ?>" class="btn btn-outline-secondary">
            Retour
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-white">
                <h5 class="mb-0">Informations générales</h5>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-md-4">Titre</dt>
                    <dd class="col-md-8"><?php echo e($delegation->title); ?></dd>

                    <?php if($delegation->code): ?>
                    <dt class="col-md-4">Code</dt>
                    <dd class="col-md-8">
                        <span class="badge bg-light text-dark border"><?php echo e($delegation->code); ?></span>
                    </dd>
                    <?php endif; ?>

                    <?php if($delegation->country): ?>
                    <dt class="col-md-4">Pays</dt>
                    <dd class="col-md-8"><?php echo e($delegation->country); ?></dd>
                    <?php endif; ?>

                    <dt class="col-md-4">Réunion associée</dt>
                    <dd class="col-md-8">
                        <?php if($delegation->meeting): ?>
                            <a href="<?php echo e(route('meetings.show', $delegation->meeting)); ?>">
                                <?php echo e($delegation->meeting->title); ?>

                            </a>
                            <?php if($delegation->meeting->start_at): ?>
                                <div class="small text-muted"><?php echo e($delegation->meeting->start_at->format('d/m/Y H:i')); ?></div>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="text-muted">Non renseignée</span>
                        <?php endif; ?>
                    </dd>

                    <dt class="col-md-4">Statut</dt>
                    <dd class="col-md-8">
                        <?php if($delegation->is_active): ?>
                            <span class="badge bg-success-subtle text-success-emphasis">
                                Actif
                            </span>
                        <?php else: ?>
                            <span class="badge bg-secondary-subtle text-secondary-emphasis">
                                Inactif
                            </span>
                        <?php endif; ?>
                    </dd>

                    <?php if($delegation->description): ?>
                    <dt class="col-md-4">Description</dt>
                    <dd class="col-md-8"><?php echo e($delegation->description); ?></dd>
                    <?php endif; ?>

                    <?php if($delegation->contact_email): ?>
                    <dt class="col-md-4">Email de contact</dt>
                    <dd class="col-md-8">
                        <a href="mailto:<?php echo e($delegation->contact_email); ?>"><?php echo e($delegation->contact_email); ?></a>
                    </dd>
                    <?php endif; ?>

                    <?php if($delegation->contact_phone): ?>
                    <dt class="col-md-4">Téléphone</dt>
                    <dd class="col-md-8"><?php echo e($delegation->contact_phone); ?></dd>
                    <?php endif; ?>

                    <?php if($delegation->address): ?>
                    <dt class="col-md-4">Adresse</dt>
                    <dd class="col-md-8"><?php echo e($delegation->address); ?></dd>
                    <?php endif; ?>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0">Utilisateurs</h5>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <strong><?php echo e($delegation->users->count()); ?></strong> utilisateur(s) associé(s)
                </p>
                <?php if($delegation->users->count() > 0): ?>
                    <ul class="list-unstyled mb-0">
                        <?php $__currentLoopData = $delegation->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="mb-2">
                                <a href="<?php echo e(route('users.show', $user)); ?>" class="text-decoration-none">
                                    <?php echo e($user->name); ?>

                                </a>
                                <?php if($user->email): ?>
                                    <div class="small text-muted"><?php echo e($user->email); ?></div>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted small mb-0">Aucun utilisateur associé</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="card shadow-sm border-0 mt-3">
            <div class="card-header bg-white">
                <h5 class="mb-0">Participants de la délégation</h5>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <strong><?php echo e($delegation->participants->count()); ?></strong> participant(s) associés
                </p>
                <?php if($delegation->participants->count() > 0): ?>
                    <ul class="list-unstyled mb-0">
                        <?php $__currentLoopData = $delegation->participants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="mb-2">
                                <a href="<?php echo e(route('users.show', $user)); ?>" class="text-decoration-none">
                                    <?php echo e($user->name); ?>

                                </a>
                                <?php if($user->email): ?>
                                    <div class="small text-muted"><?php echo e($user->email); ?></div>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted small mb-0">Aucun participant associé</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\delegations\show.blade.php ENDPATH**/ ?>