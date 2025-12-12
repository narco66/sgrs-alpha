<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1"><?php echo e($user->name); ?></h4>
        <p class="text-muted mb-0">
            Profil utilisateur
        </p>
    </div>
    <div class="btn-group">
        <?php
            $canAdminUsers = auth()->user()->hasAnyRole(['super-admin', 'admin', 'dsi'])
                || auth()->user()->can('users.manage');
        ?>
        <?php if($canAdminUsers): ?>
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

                <?php
                    $status = $user->status ?? ($user->is_active ? 'active' : 'inactive');
                ?>

                <?php if($status === 'active'): ?>
                    <span class="badge bg-success mb-3">Compte actif</span>
                <?php elseif($status === 'pending'): ?>
                    <span class="badge bg-warning text-dark mb-3">Compte en attente de validation</span>
                <?php elseif($status === 'rejected'): ?>
                    <span class="badge bg-danger mb-3">Compte rejeté</span>
                <?php else: ?>
                    <span class="badge bg-secondary mb-3">Compte inactif</span>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('toggleActive', $user)): ?>
                    <?php if($status === 'pending'): ?>
                        
                        <form method="POST" action="<?php echo e(route('users.approve', $user)); ?>" class="mt-3 d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="bi bi-check-circle me-1"></i> Valider le compte
                            </button>
                        </form>
                        <button type="button" class="btn btn-sm btn-outline-danger mt-3" data-bs-toggle="modal" data-bs-target="#rejectUserModal">
                            <i class="bi bi-x-circle me-1"></i> Rejeter le compte
                        </button>
                    <?php else: ?>
                        
                        <form method="POST" action="<?php echo e(route('users.toggle-active', $user)); ?>" class="mt-3">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-sm btn-outline-<?php echo e($user->is_active ? 'danger' : 'success'); ?>">
                                <?php echo e($user->is_active ? 'Désactiver' : 'Activer'); ?> le compte
                            </button>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
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

        <?php if(isset($statusLogs) && $statusLogs->count() > 0): ?>
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Historique du statut du compte</h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <?php $__currentLoopData = $statusLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $oldStatus = $log->old_values['status'] ?? null;
                            $newStatus = $log->new_values['status'] ?? null;

                            $formatStatus = function (?string $s) {
                                return match($s) {
                                    'active'   => 'actif',
                                    'inactive' => 'inactif',
                                    'pending'  => 'en attente',
                                    'rejected' => 'rejeté',
                                    default    => $s ?? 'inconnu',
                                };
                            };

                            $label = match($log->event) {
                                'user_registration_requested' => 'Demande de création de compte',
                                'user_account_approved'       => 'Compte validé',
                                'user_account_rejected'       => 'Compte rejeté',
                                'created'                     => 'Création du compte',
                                'updated'                     => 'Mise à jour du compte',
                                default                       => ucfirst(str_replace('_', ' ', $log->event)),
                            };
                        ?>
                        <div class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="me-3">
                                <div class="fw-semibold"><?php echo e($label); ?></div>
                                <div class="small text-muted">
                                    <?php if($oldStatus || $newStatus): ?>
                                        <?php if($oldStatus && $newStatus): ?>
                                            Statut : <?php echo e($formatStatus($oldStatus)); ?> → <?php echo e($formatStatus($newStatus)); ?>

                                        <?php elseif($newStatus): ?>
                                            Nouveau statut : <?php echo e($formatStatus($newStatus)); ?>

                                        <?php else: ?>
                                            Ancien statut : <?php echo e($formatStatus($oldStatus)); ?>

                                        <?php endif; ?>
                                    <?php else: ?>
                                        Détail indisponible.
                                    <?php endif; ?>
                                </div>
                                <?php if($log->user): ?>
                                    <div class="small text-muted">
                                        Par : <?php echo e($log->user->name); ?> (<?php echo e($log->user->email); ?>)
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="text-end small text-muted">
                                <?php echo e($log->created_at?->format('d/m/Y H:i')); ?>

                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

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

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('toggleActive', $user)): ?>
    
    <div class="modal fade" id="rejectUserModal" tabindex="-1" aria-labelledby="rejectUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectUserModalLabel">
                        <i class="bi bi-x-circle me-1 text-danger"></i>
                        Rejeter le compte utilisateur
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <form method="POST" action="<?php echo e(route('users.reject', $user)); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <p class="mb-3">
                            Vous êtes sur le point de rejeter la demande de création de compte de
                            <strong><?php echo e($user->name); ?></strong> (<?php echo e($user->email); ?>).
                        </p>
                        <div class="mb-3">
                            <label for="reject-reason" class="form-label">Motif du rejet (optionnel)</label>
                            <textarea
                                id="reject-reason"
                                name="reason"
                                class="form-control"
                                rows="4"
                                placeholder="Exemple : Informations incomplètes, compte déjà existant, etc."
                            ></textarea>
                            <div class="form-text">
                                Ce motif pourra être communiqué à l'utilisateur dans l'e-mail de notification.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Annuler
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-x-circle me-1"></i>
                            Confirmer le rejet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views/users/show.blade.php ENDPATH**/ ?>