<?php $__env->startSection('content'); ?>
<?php
    use App\Models\MeetingParticipant;

    $statusClasses = [
        MeetingParticipant::STATUS_INVITED   => 'bg-secondary-subtle text-secondary-emphasis',
        MeetingParticipant::STATUS_CONFIRMED => 'bg-primary-subtle text-primary-emphasis',
        MeetingParticipant::STATUS_PRESENT   => 'bg-success-subtle text-success-emphasis',
        MeetingParticipant::STATUS_EXCUSED   => 'bg-warning-subtle text-warning-emphasis',
        MeetingParticipant::STATUS_ABSENT    => 'bg-danger-subtle text-danger-emphasis',
    ];

    $statusIcons = [
        MeetingParticipant::STATUS_INVITED   => 'bi-envelope',
        MeetingParticipant::STATUS_CONFIRMED => 'bi-check-circle',
        MeetingParticipant::STATUS_PRESENT   => 'bi-person-check',
        MeetingParticipant::STATUS_EXCUSED   => 'bi-emoji-frown',
        MeetingParticipant::STATUS_ABSENT    => 'bi-x-circle',
    ];

    $statusLabels = [
        MeetingParticipant::STATUS_INVITED   => 'Invité',
        MeetingParticipant::STATUS_CONFIRMED => 'Confirmé',
        MeetingParticipant::STATUS_PRESENT   => 'Présent',
        MeetingParticipant::STATUS_EXCUSED   => 'Excusé',
        MeetingParticipant::STATUS_ABSENT    => 'Absent',
    ];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Participants de la réunion</h4>
        <p class="text-muted mb-0">
            <?php echo e($meeting->title); ?> — <?php echo e($meeting->start_at?->format('d/m/Y H:i')); ?>

        </p>
    </div>
    <a href="<?php echo e(route('meetings.show', $meeting)); ?>" class="btn btn-outline-secondary">
        Retour à la réunion
    </a>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <?php echo $__env->make('partials.alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <form method="POST" action="<?php echo e(route('meetings.participants.store', $meeting)); ?>" class="row g-2 align-items-end">
            <?php echo csrf_field(); ?>

            <div class="col-md-6">
                <label class="form-label small">Ajouter des participants</label>
                <select name="user_ids[]" class="form-select" multiple required>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($user->id); ?>">
                            <?php echo e($user->name); ?> — <?php echo e($user->email); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <div class="form-text">
                    Maintenez Ctrl (ou Cmd) pour sélectionner plusieurs collaborateurs.
                </div>
            </div>

            <div class="col-md-3">
                <label class="form-label small">Rôle par défaut</label>
                <input type="text" name="role" class="form-control" value="<?php echo e(old('role', 'Participant')); ?>">
            </div>

            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-person-plus me-1"></i> Ajouter
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light small text-muted">
                    <tr>
                        <th>Participant</th>
                        <th>Rôle</th>
                        <th>Statut</th>
                        <th>Validation / Présence</th>
                        <th>Rappel</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $participants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $participant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $status = $participant->status;
                        $rowClass = match($status) {
                            MeetingParticipant::STATUS_PRESENT   => 'table-success',
                            MeetingParticipant::STATUS_CONFIRMED => 'table-primary',
                            MeetingParticipant::STATUS_EXCUSED   => 'table-warning',
                            MeetingParticipant::STATUS_ABSENT    => 'table-danger',
                            default                               => '',
                        };
                    ?>
                    <tr class="<?php echo e($rowClass); ?>">
                        <td>
                            <div class="fw-semibold">
                                <?php echo e($participant->user?->name ?? 'Utilisateur supprimé'); ?>

                            </div>
                            <div class="small text-muted">
                                <?php echo e($participant->user?->email); ?>

                            </div>
                        </td>
                        <td>
                            <?php echo e($participant->role ?: 'Participant'); ?>

                        </td>
                        <td>
                            <span class="badge <?php echo e($statusClasses[$status] ?? 'bg-light text-muted'); ?>">
                                <i class="bi <?php echo e($statusIcons[$status] ?? 'bi-dot'); ?> me-1"></i>
                                <?php echo e($statusLabels[$status] ?? ucfirst($status)); ?>

                            </span>
                        </td>
                        <td class="small">
                            <?php if($participant->validated_at): ?>
                                <div>
                                    <i class="bi bi-check2-circle text-success me-1"></i>
                                    Validé le <?php echo e($participant->validated_at->format('d/m/Y H:i')); ?>

                                </div>
                            <?php else: ?>
                                <div class="text-muted">
                                    <i class="bi bi-hourglass-split me-1"></i> En attente de validation
                                </div>
                            <?php endif; ?>

                            <?php if($participant->checked_in_at): ?>
                                <div>
                                    <i class="bi bi-person-check text-success me-1"></i>
                                    Présent le <?php echo e($participant->checked_in_at->format('d/m/Y H:i')); ?>

                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($participant->reminder_sent): ?>
                                <span class="badge bg-success-subtle text-success-emphasis">
                                    <i class="bi bi-bell-fill me-1"></i> Envoyé
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary-subtle text-secondary-emphasis">
                                    <i class="bi bi-bell-slash me-1"></i> Non envoyé
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            
                            <div class="btn-group btn-group-sm" role="group">
                                
                                <form method="POST" action="<?php echo e(route('meetings.participants.update-status', [$meeting, $participant])); ?>">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <input type="hidden" name="status" value="<?php echo e(MeetingParticipant::STATUS_INVITED); ?>">
                                    <button type="submit"
                                            class="btn btn-outline-secondary"
                                            title="Inviter / Réinviter">
                                        <i class="bi bi-envelope"></i>
                                    </button>
                                </form>

                                
                                <form method="POST" action="<?php echo e(route('meetings.participants.update-status', [$meeting, $participant])); ?>">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <input type="hidden" name="status" value="<?php echo e(MeetingParticipant::STATUS_CONFIRMED); ?>">
                                    <button type="submit"
                                            class="btn btn-outline-primary"
                                            title="Marquer comme confirmé">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                </form>

                                
                                <form method="POST" action="<?php echo e(route('meetings.participants.update-status', [$meeting, $participant])); ?>">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <input type="hidden" name="status" value="<?php echo e(MeetingParticipant::STATUS_PRESENT); ?>">
                                    <button type="submit"
                                            class="btn btn-outline-success"
                                            title="Marquer comme présent">
                                        <i class="bi bi-person-check"></i>
                                    </button>
                                </form>

                                
                                <form method="POST" action="<?php echo e(route('meetings.participants.update-status', [$meeting, $participant])); ?>">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <input type="hidden" name="status" value="<?php echo e(MeetingParticipant::STATUS_EXCUSED); ?>">
                                    <button type="submit"
                                            class="btn btn-outline-warning"
                                            title="Marquer comme excusé">
                                        <i class="bi bi-emoji-frown"></i>
                                    </button>
                                </form>
                            </div>

                            
                            <form method="POST"
                                  action="<?php echo e(route('meetings.participants.destroy', [$meeting, $participant])); ?>"
                                  class="d-inline"
                                  onsubmit="return confirm('Retirer ce participant de la réunion ?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit"
                                        class="btn btn-sm btn-outline-danger ms-1"
                                        title="Retirer">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Aucun participant enregistré pour cette réunion.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\meetings\participants\index.blade.php ENDPATH**/ ?>