

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1"><?php echo e($user->name); ?></h4>
        <p class="text-muted mb-0">
            Profil utilisateur
        </p>
    </div>
    <div class="btn-group">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $user)): ?>
        <a href="<?php echo e(route('users.edit', $user)); ?>" class="btn btn-outline-primary">
            <i class="bi bi-pencil me-1"></i> Modifier
        </a>
        <?php endif; ?>
        <a href="<?php echo e(route('users.index')); ?>" class="btn btn-outline-secondary">
            Retour à la liste
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                    <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                </div>
                <h5 class="mb-1"><?php echo e($user->name); ?></h5>
                <?php if($user->first_name || $user->last_name): ?>
                    <p class="text-muted mb-2"><?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?></p>
                <?php endif; ?>
                <p class="text-muted mb-3"><?php echo e($user->email); ?></p>
                
                <?php if($user->is_active): ?>
                    <span class="badge bg-success mb-3">Compte actif</span>
                <?php else: ?>
                    <span class="badge bg-danger mb-3">Compte inactif</span>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('toggleActive', $user)): ?>
                <form method="POST" action="<?php echo e(route('users.toggle-active', $user)); ?>" class="mt-3">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-sm btn-outline-<?php echo e($user->is_active ? 'danger' : 'success'); ?>">
                        <?php echo e($user->is_active ? 'Désactiver' : 'Activer'); ?> le compte
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-white">
                <h6 class="mb-0">Informations personnelles</h6>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Email</dt>
                    <dd class="col-sm-8"><?php echo e($user->email); ?></dd>

                    <?php if($user->service): ?>
                    <dt class="col-sm-4">Service</dt>
                    <dd class="col-sm-8"><?php echo e($user->service); ?></dd>
                    <?php endif; ?>

                    <?php if($user->delegation): ?>
                    <dt class="col-sm-4">Délégation</dt>
                    <dd class="col-sm-8">
                        <a href="<?php echo e(route('delegations.show', $user->delegation)); ?>">
                            <?php echo e($user->delegation->title); ?>

                        </a>
                    </dd>
                    <?php endif; ?>

                    <dt class="col-sm-4">Rôles</dt>
                    <dd class="col-sm-8">
                        <?php $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="badge bg-secondary me-1"><?php echo e($role->name); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </dd>

                    <dt class="col-sm-4">Date d'inscription</dt>
                    <dd class="col-sm-8"><?php echo e($user->created_at->format('d/m/Y H:i')); ?></dd>

                    <?php if($user->email_verified_at): ?>
                    <dt class="col-sm-4">Email vérifié</dt>
                    <dd class="col-sm-8"><?php echo e($user->email_verified_at->format('d/m/Y H:i')); ?></dd>
                    <?php endif; ?>
                </dl>
            </div>
        </div>

        <?php if($user->organizedMeetings->count() > 0): ?>
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-white">
                <h6 class="mb-0">Réunions organisées (<?php echo e($user->organizedMeetings->count()); ?>)</h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <?php $__currentLoopData = $user->organizedMeetings->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meeting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(!$meeting) continue; ?>
                        <a href="<?php echo e(route('meetings.show', $meeting)); ?>" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-1"><?php echo e($meeting->title); ?></h6>
                                    <small class="text-muted"><?php echo e($meeting->start_at->format('d/m/Y H:i')); ?></small>
                                </div>
                                <span class="badge bg-<?php echo e($meeting->status === 'terminee' ? 'success' : 'primary'); ?>">
                                    <?php echo e($meeting->status); ?>

                                </span>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php if($user->organizedMeetings->count() > 5): ?>
                    <div class="mt-3 text-center">
                        <a href="<?php echo e(route('meetings.index', ['organizer' => $user->id])); ?>" class="btn btn-sm btn-outline-primary">
                            Voir toutes les réunions
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if($user->meetingParticipations->count() > 0): ?>
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h6 class="mb-0">Participations aux réunions (<?php echo e($user->meetingParticipations->count()); ?>)</h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <?php $__currentLoopData = $user->meetingParticipations->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $participation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $meeting = $participation->meeting; ?>
                        <?php if(!$meeting) continue; ?>
                        <a href="<?php echo e(route('meetings.show', $meeting)); ?>" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-1"><?php echo e($meeting->title); ?></h6>
                                    <small class="text-muted"><?php echo e($meeting->start_at->format('d/m/Y H:i')); ?></small>
                                </div>
                                <span class="badge bg-<?php echo e($participation->status === 'confirmed' ? 'success' : 'warning'); ?>">
                                    <?php echo e($participation->status); ?>

                                </span>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\users\show.blade.php ENDPATH**/ ?>