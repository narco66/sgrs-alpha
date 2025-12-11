<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Gestion des salles de réunions</h4>
        <div class="small">
            <a href="<?php echo e(route('dashboard')); ?>" class="text-decoration-none text-muted">Accueil</a>
            <span class="text-muted">/</span>
            <span class="text-muted">Salles de réunions</span>
        </div>
        <p class="text-muted mb-0 mt-1">Visualisation des disponibilités et des réunions prévues par salle.</p>
    </div>
</div>

<?php
    // Valeur courante du filtre :
    // 1) priorité à la variable transmise par le contrôleur ($filter)
    // 2) sinon, lecture du paramètre de requête ?filter=
    // 3) sinon, valeur par défaut : 'all'
    $currentFilter = $filter ?? request('filter', 'all');
?>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body d-flex flex-wrap gap-2">
        <a href="<?php echo e(route('rooms.index', ['filter' => 'all'])); ?>"
           class="btn btn-sm <?php echo e($currentFilter === 'all' ? 'btn-primary' : 'btn-outline-primary'); ?>">
            Toutes les salles
        </a>
        <a href="<?php echo e(route('rooms.index', ['filter' => 'available'])); ?>"
           class="btn btn-sm <?php echo e($currentFilter === 'available' ? 'btn-primary' : 'btn-outline-primary'); ?>">
            Salles disponibles
        </a>
        <a href="<?php echo e(route('rooms.index', ['filter' => 'occupied'])); ?>"
           class="btn btn-sm <?php echo e($currentFilter === 'occupied' ? 'btn-primary' : 'btn-outline-primary'); ?>">
            Salles occupées
        </a>
    </div>
</div>

<div class="row g-3">
    <?php $__empty_1 = true; $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title mb-0"><?php echo e($room->name); ?></h5>
                        <?php if($room->is_occupied): ?>
                            <span class="badge bg-danger">Occupée</span>
                        <?php else: ?>
                            <span class="badge bg-success">Disponible</span>
                        <?php endif; ?>
                    </div>
                    <p class="text-muted small mb-2">
                        <?php echo e($room->location ?? 'Localisation non renseignée'); ?>

                    </p>

                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between small mb-1">
                            <span>Capacité</span>
                            <span><?php echo e($room->capacity); ?> personne<?php echo e($room->capacity > 1 ? 's' : ''); ?></span>
                        </div>
                        <?php
                            $ratio = 0;
                            $participantsCount = 0;

                            if ($room->is_occupied && $room->current_meeting) {
                                $participantsCount = $room->current_meeting->participants->count() ?? 0;
                                $ratio = min(
                                    100,
                                    round($participantsCount * 100 / max(1, (int) $room->capacity))
                                );
                            }
                        ?>
                        <div class="progress" style="height:8px;">
                            <div class="progress-bar
                                        <?php if($ratio < 70): ?> bg-success
                                        <?php elseif($ratio < 100): ?> bg-warning
                                        <?php else: ?> bg-danger
                                        <?php endif; ?>"
                                 role="progressbar"
                                 style="width: <?php echo e($ratio); ?>%;"></div>
                        </div>
                    </div>

                    
                    <div class="mb-3">
                        <div class="small text-muted mb-1">Équipements présents dans la salle</div>
                        <?php if($room->equipments && count($room->equipments)): ?>
                            <?php $__currentLoopData = $room->equipments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $equipment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="badge bg-light text-dark border me-1 mb-1">
                                    <?php echo e($equipment); ?>

                                </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <span class="text-muted small">Aucun équipement renseigné.</span>
                        <?php endif; ?>
                    </div>

                    <hr>

                    
                    <?php if($room->is_occupied && $room->current_meeting): ?>
                        <?php
                            $meeting   = $room->current_meeting;
                            $startTime = $meeting->start_at?->format('H:i');
                            $endTime   = $meeting->start_at
                                ? $meeting->start_at->copy()->addMinutes($meeting->duration_minutes)->format('H:i')
                                : null;
                            $meetingParticipants = $meeting->participants->count() ?? 0;
                        ?>

                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold">
                                    <?php echo e($meeting->title); ?>

                                </div>
                                <div class="text-muted small">
                                    <?php if($startTime && $endTime): ?>
                                        <?php echo e($startTime); ?> – <?php echo e($endTime); ?>

                                    <?php elseif($startTime): ?>
                                        <?php echo e($startTime); ?>

                                    <?php endif; ?>
                                    • <?php echo e($meetingParticipants); ?> personne<?php echo e($meetingParticipants > 1 ? 's' : ''); ?>

                                </div>
                            </div>
                            <a href="<?php echo e(route('meetings.show', $meeting)); ?>"
                               class="btn btn-sm btn-outline-danger">
                                Voir plus
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="text-center text-muted py-3">
                            <div class="mb-2">
                                <i class="bi bi-calendar-plus fs-2"></i>
                            </div>
                            <div class="fw-semibold mb-1">Aucune réunion prévue dans cette salle</div>
                            <a href="<?php echo e(route('meetings.create', ['room_id' => $room->id])); ?>"
                               class="btn btn-sm btn-primary">
                                Organiser une réunion dans cette salle
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="text-muted">Aucune salle enregistrée.</p>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views/rooms/index.blade.php ENDPATH**/ ?>