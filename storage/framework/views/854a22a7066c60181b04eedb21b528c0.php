<?php $__env->startSection('content'); ?>
<?php
    $hasMembers = $delegation->members && $delegation->members->count() > 0;
    $hasHeadOfDelegation = !empty($delegation->head_of_delegation_name);
    $canGenerateBadges = $hasMembers || $hasHeadOfDelegation;
?>

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
        
        
        <div class="dropdown">
            <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-file-earmark-pdf me-1"></i> Exports PDF
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="<?php echo e(route('delegations.pdf', $delegation)); ?>">
                        <i class="bi bi-file-text me-2"></i> Fiche de la délégation
                    </a>
                </li>
                <?php if($canGenerateBadges): ?>
                <li><hr class="dropdown-divider"></li>
                <li><h6 class="dropdown-header">Badges participants</h6></li>
                <li>
                    <a class="dropdown-item" href="<?php echo e(route('delegations.badges', $delegation)); ?>">
                        <i class="bi bi-person-badge me-2"></i> Tous les badges (<?php echo e($hasMembers ? $delegation->members->count() : 0); ?><?php echo e($hasHeadOfDelegation ? ' + Chef' : ''); ?>)
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
        
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
        
        <?php if($hasHeadOfDelegation): ?>
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-danger-subtle">
                <h5 class="mb-0 text-danger-emphasis">
                    <i class="bi bi-person-fill me-1"></i> Chef de Délégation
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-danger-subtle rounded-circle p-2 me-3">
                        <i class="bi bi-person-badge fs-4 text-danger"></i>
                    </div>
                    <div>
                        <h6 class="mb-0"><?php echo e($delegation->head_of_delegation_name); ?></h6>
                        <?php if($delegation->head_of_delegation_position): ?>
                            <small class="text-muted"><?php echo e($delegation->head_of_delegation_position); ?></small>
                        <?php endif; ?>
                        <?php if($delegation->head_of_delegation_email): ?>
                            <div class="small">
                                <a href="mailto:<?php echo e($delegation->head_of_delegation_email); ?>">
                                    <?php echo e($delegation->head_of_delegation_email); ?>

                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-people me-1"></i> Membres de la délégation
                </h5>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $delegation)): ?>
                <a href="<?php echo e(route('delegations.members.create', $delegation)); ?>" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-plus"></i> Ajouter
                </a>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <?php if($hasMembers): ?>
                    <p class="mb-3">
                        <strong><?php echo e($delegation->members->count()); ?></strong> membre(s) enregistré(s)
                    </p>
                    <div class="list-group list-group-flush">
                        <?php $__currentLoopData = $delegation->members->sortBy(function($m) { return $m->role === 'head' ? 0 : 1; }); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="list-group-item px-0 d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="fw-semibold">
                                        <?php echo e($member->full_name); ?>

                                        <?php if($member->role === 'head'): ?>
                                            <span class="badge bg-danger-subtle text-danger-emphasis ms-1">Chef</span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if($member->position): ?>
                                        <small class="text-muted d-block"><?php echo e($member->position); ?></small>
                                    <?php endif; ?>
                                    <?php if($member->email): ?>
                                        <small class="text-muted"><?php echo e($member->email); ?></small>
                                    <?php endif; ?>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="<?php echo e(route('delegations.members.badge', [$delegation, $member])); ?>">
                                                <i class="bi bi-person-badge me-2"></i> Badge PDF
                                            </a>
                                        </li>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $delegation)): ?>
                                        <li>
                                            <a class="dropdown-item" href="<?php echo e(route('delegations.members.edit', [$delegation, $member])); ?>">
                                                <i class="bi bi-pencil me-2"></i> Modifier
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="<?php echo e(route('delegations.members.destroy', [$delegation, $member])); ?>" method="POST" onsubmit="return confirm('Supprimer ce membre ?')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="bi bi-trash me-2"></i> Supprimer
                                                </button>
                                            </form>
                                        </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    <?php if($canGenerateBadges): ?>
                    <div class="mt-3 pt-3 border-top">
                        <a href="<?php echo e(route('delegations.badges', $delegation)); ?>" class="btn btn-outline-primary btn-sm w-100">
                            <i class="bi bi-person-badge me-1"></i> Générer tous les badges PDF
                        </a>
                    </div>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="text-muted mb-0">
                        Aucun membre enregistré.
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $delegation)): ?>
                        <a href="<?php echo e(route('delegations.members.create', $delegation)); ?>">Ajouter un membre</a>
                        <?php endif; ?>
                    </p>
                    <?php if($hasHeadOfDelegation): ?>
                    <div class="mt-3 pt-3 border-top">
                        <p class="small text-muted mb-2">
                            <i class="bi bi-info-circle"></i> Le Chef de Délégation peut générer un badge.
                        </p>
                        <a href="<?php echo e(route('delegations.badges', $delegation)); ?>" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-person-badge me-1"></i> Badge du Chef de Délégation
                        </a>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views/delegations/show.blade.php ENDPATH**/ ?>