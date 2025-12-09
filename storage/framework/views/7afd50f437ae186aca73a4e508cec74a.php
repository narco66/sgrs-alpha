<?php $__env->startSection('title', $room->name . ' – Salle de réunion'); ?>

<?php $__env->startSection('content'); ?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('dashboard')); ?>">Accueil</a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('rooms.index')); ?>">Salles de réunion</a>
        </li>
        <li class="breadcrumb-item active"><?php echo e($room->name); ?></li>
    </ol>
</nav>


<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="d-flex align-items-center mb-1">
            <h3 class="page-title mb-0 me-3"><?php echo e($room->name); ?></h3>
            <?php if($room->is_occupied): ?>
                <span class="badge-modern badge-modern-danger">
                    <i class="bi bi-clock-fill me-1"></i>
                    Occupée
                </span>
            <?php elseif(!$room->is_active): ?>
                <span class="badge-modern badge-modern-secondary">
                    <i class="bi bi-pause-circle me-1"></i>
                    Inactive
                </span>
            <?php else: ?>
                <span class="badge-modern badge-modern-success">
                    <i class="bi bi-check-circle me-1"></i>
                    Disponible
                </span>
            <?php endif; ?>
        </div>
        <p class="text-muted mb-0 small">
            Fiche détaillée de la salle de réunion et historique d’utilisation.
        </p>
    </div>
    <div class="d-flex gap-2">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $room)): ?>
            <a href="<?php echo e(route('rooms.edit', $room)); ?>" class="btn btn-modern btn-modern-primary">
                <i class="bi bi-pencil me-1"></i>
                Modifier
            </a>
        <?php endif; ?>
        <a href="<?php echo e(route('rooms.index')); ?>" class="btn btn-modern btn-modern-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Retour à la liste
        </a>
    </div>
</div>


<?php if(session('success')): ?>
    <?php if (isset($component)) { $__componentOriginal682e217f64e93fddc2bc39228ca2c21e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal682e217f64e93fddc2bc39228ca2c21e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modern-alert','data' => ['type' => 'success','dismissible' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modern-alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'success','dismissible' => true]); ?>
        <?php echo e(session('success')); ?>

     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal682e217f64e93fddc2bc39228ca2c21e)): ?>
<?php $attributes = $__attributesOriginal682e217f64e93fddc2bc39228ca2c21e; ?>
<?php unset($__attributesOriginal682e217f64e93fddc2bc39228ca2c21e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal682e217f64e93fddc2bc39228ca2c21e)): ?>
<?php $component = $__componentOriginal682e217f64e93fddc2bc39228ca2c21e; ?>
<?php unset($__componentOriginal682e217f64e93fddc2bc39228ca2c21e); ?>
<?php endif; ?>
<?php endif; ?>

<div class="row g-4">
    
    <div class="col-lg-8">
        
        <div class="modern-card mb-4">
            <div class="modern-card-header">
                <h5 class="modern-card-title">
                    <i class="bi bi-door-open text-primary me-2"></i>
                    Photo et informations principales
                </h5>
            </div>
            <div class="row g-0">
                <div class="col-md-5">
                    <div class="h-100" style="min-height: 260px;">
                        <?php if($room->image): ?>
                            
                            <img src="<?php echo e($room->image_url); ?>"
                                 alt="<?php echo e($room->name); ?>"
                                 class="img-fluid h-100 w-100 rounded-start"
                                 style="object-fit: cover;">
                        <?php else: ?>
                            <div class="d-flex align-items-center justify-content-center h-100 bg-light rounded-start">
                                <div class="text-center text-muted">
                                    <i class="bi bi-door-open" style="font-size: 3.5rem;"></i>
                                    <p class="mb-0 mt-2 small">Aucune image enregistrée</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="modern-card-body h-100 d-flex flex-column">
                        <div class="mb-3">
                            <span class="badge bg-light text-dark border mb-2">
                                <i class="bi bi-tag me-1"></i>
                                <?php echo e($room->code ?? 'N/A'); ?>

                            </span>
                            <h4 class="fw-bold mb-1"><?php echo e($room->name); ?></h4>
                            <?php if($room->location): ?>
                                <p class="text-muted mb-0">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    <?php echo e($room->location); ?>

                                </p>
                            <?php endif; ?>
                        </div>

                        
                        <?php if($room->description): ?>
                            <div class="mb-3">
                                <p class="text-muted"><?php echo e($room->description); ?></p>
                            </div>
                        <?php endif; ?>

                        
                        <div class="mb-3">
                            <div class="d-flex align-items-center">
                                <div class="me-4">
                                    <span class="text-muted small">Capacité</span>
                                    <h3 class="mb-0 text-primary">
                                        <?php echo e($room->capacity); ?>

                                        <small class="text-muted fs-6">personnes</small>
                                    </h3>
                                </div>
                            </div>
                        </div>

                        
                        <div class="mt-auto">
                            <?php if($room->is_active && !$room->is_occupied): ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\Meeting::class)): ?>
                                    <a href="<?php echo e(route('meetings.create', ['room_id' => $room->id])); ?>"
                                       class="btn btn-modern btn-modern-primary">
                                        <i class="bi bi-plus-circle me-1"></i>
                                        Organiser une réunion
                                    </a>
                                <?php endif; ?>
                            <?php elseif($room->is_occupied && $room->current_meeting): ?>
                                <a href="<?php echo e(route('meetings.show', $room->current_meeting)); ?>"
                                   class="btn btn-modern btn-modern-danger">
                                    <i class="bi bi-eye me-1"></i>
                                    Voir la réunion en cours
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="modern-card mb-4">
            <div class="modern-card-header">
                <h5 class="modern-card-title">
                    <i class="bi bi-tools text-primary me-2"></i>
                    Équipements disponibles
                </h5>
            </div>
            <div class="modern-card-body">
                <?php if($room->equipments && count($room->equipments) > 0): ?>
                    <div class="row g-3">
                        <?php $__currentLoopData = $room->equipments_with_labels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $equip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-4 col-sm-6">
                                <div class="d-flex align-items-center p-3 border rounded bg-light">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <span><?php echo e($equip['label']); ?></span>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-tools fs-1"></i>
                        <p class="mb-0 mt-2">Aucun équipement renseigné pour cette salle.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="modern-card mb-4">
            <div class="modern-card-header d-flex justify-content-between align-items-center">
                <h5 class="modern-card-title mb-0">
                    <i class="bi bi-calendar-event text-primary me-2"></i>
                    Réunions à venir
                </h5>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\Meeting::class)): ?>
                    <a href="<?php echo e(route('meetings.create', ['room_id' => $room->id])); ?>"
                       class="btn btn-sm btn-modern btn-modern-secondary">
                        <i class="bi bi-plus"></i>
                        Planifier
                    </a>
                <?php endif; ?>
            </div>
            <div class="modern-card-body p-0">
                <?php if($upcomingMeetings->count() > 0): ?>
                    <div class="list-group list-group-flush">
                        <?php $__currentLoopData = $upcomingMeetings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meeting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('meetings.show', $meeting)); ?>"
                               class="list-group-item list-group-item-action py-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1 fw-semibold"><?php echo e($meeting->title); ?></h6>
                                        <div class="text-muted small">
                                            <i class="bi bi-calendar me-1"></i>
                                            <?php echo e($meeting->start_at->format('d/m/Y')); ?>

                                            <i class="bi bi-clock ms-2 me-1"></i>
                                            <?php echo e($meeting->start_at->format('H:i')); ?>

                                            <?php if($meeting->duration_minutes): ?>
                                                - <?php echo e($meeting->start_at->copy()->addMinutes($meeting->duration_minutes)->format('H:i')); ?>

                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <span class="badge-modern badge-modern-primary">
                                        <?php echo e(ucfirst($meeting->status)); ?>

                                    </span>
                                </div>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-calendar-x fs-1"></i>
                        <p class="mb-0 mt-2">Aucune réunion programmée dans cette salle.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
        <?php if($pastMeetings->count() > 0): ?>
            <div class="modern-card">
                <div class="modern-card-header">
                    <h5 class="modern-card-title">
                        <i class="bi bi-clock-history text-primary me-2"></i>
                        Réunions passées
                    </h5>
                </div>
                <div class="modern-card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php $__currentLoopData = $pastMeetings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meeting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('meetings.show', $meeting)); ?>"
                               class="list-group-item list-group-item-action py-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1"><?php echo e($meeting->title); ?></h6>
                                        <div class="text-muted small">
                                            <i class="bi bi-calendar me-1"></i>
                                            <?php echo e($meeting->start_at->format('d/m/Y \à H:i')); ?>

                                        </div>
                                    </div>
                                    <span class="badge-modern badge-modern-secondary">
                                        <?php echo e(ucfirst($meeting->status)); ?>

                                    </span>
                                </div>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    
    <div class="col-lg-4">
        
        <div class="modern-card mb-4 <?php echo e($room->is_occupied ? 'border-danger' : ($room->is_active ? 'border-success' : 'border-secondary')); ?>"
             style="border-width: 2px !important;">
            <div class="modern-card-body text-center py-4">
                <?php if($room->is_occupied): ?>
                    <div class="text-danger mb-2">
                        <i class="bi bi-clock-fill" style="font-size: 3rem;"></i>
                    </div>
                    <h4 class="text-danger mb-2">Salle occupée</h4>
                    <?php if($room->current_meeting): ?>
                        <p class="text-muted mb-0">
                            <?php echo e($room->current_meeting->title); ?>

                        </p>
                        <p class="small text-muted">
                            Jusqu’à
                            <?php echo e($room->current_meeting->end_at?->format('H:i') 
                                ?? $room->current_meeting->start_at?->copy()->addMinutes($room->current_meeting->duration_minutes ?? 60)->format('H:i')); ?>

                        </p>
                    <?php endif; ?>
                <?php elseif(!$room->is_active): ?>
                    <div class="text-secondary mb-2">
                        <i class="bi bi-pause-circle" style="font-size: 3rem;"></i>
                    </div>
                    <h4 class="text-secondary mb-2">Salle inactive</h4>
                    <p class="text-muted mb-0">
                        Cette salle n’est pas disponible à la réservation.
                    </p>
                <?php else: ?>
                    <div class="text-success mb-2">
                        <i class="bi bi-check-circle" style="font-size: 3rem;"></i>
                    </div>
                    <h4 class="text-success mb-2">Salle disponible</h4>
                    <p class="text-muted mb-0">
                        Cette salle est libre et peut être réservée.
                    </p>
                <?php endif; ?>
            </div>
        </div>

        
        <?php if($room->next_meeting && !$room->is_occupied): ?>
            <div class="modern-card mb-4 bg-light">
                <div class="modern-card-body">
                    <h6 class="text-muted mb-3">
                        <i class="bi bi-calendar-check me-1"></i>
                        Prochaine réunion
                    </h6>
                    <h5 class="fw-semibold mb-2"><?php echo e($room->next_meeting->title); ?></h5>
                    <div class="text-muted small">
                        <div class="mb-1">
                            <i class="bi bi-calendar me-1"></i>
                            <?php echo e($room->next_meeting->start_at->format('d/m/Y')); ?>

                        </div>
                        <div>
                            <i class="bi bi-clock me-1"></i>
                            <?php echo e($room->next_meeting->start_at->format('H:i')); ?>

                        </div>
                    </div>
                    <a href="<?php echo e(route('meetings.show', $room->next_meeting)); ?>"
                       class="btn btn-sm btn-modern btn-modern-secondary mt-3">
                        Voir les détails
                    </a>
                </div>
            </div>
        <?php endif; ?>

        
        <div class="modern-card mb-4">
            <div class="modern-card-header">
                <h5 class="modern-card-title">
                    <i class="bi bi-info-circle text-primary me-2"></i>
                    Informations
                </h5>
            </div>
            <div class="modern-card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-muted small">Code</div>
                        <div class="fw-semibold"><?php echo e($room->code ?? 'N/A'); ?></div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">Capacité</div>
                        <div class="fw-semibold"><?php echo e($room->capacity); ?> personnes</div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted small">Localisation</div>
                        <div class="fw-semibold"><?php echo e($room->location ?? 'Non renseignée'); ?></div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">Créée le</div>
                        <div class="fw-semibold"><?php echo e($room->created_at?->format('d/m/Y')); ?></div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">Modifiée le</div>
                        <div class="fw-semibold"><?php echo e($room->updated_at?->format('d/m/Y')); ?></div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="modern-card">
            <div class="modern-card-header">
                <h5 class="modern-card-title">
                    <i class="bi bi-gear text-primary me-2"></i>
                    Actions
                </h5>
            </div>
            <div class="modern-card-body">
                <div class="d-grid gap-2">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\Meeting::class)): ?>
                        <a href="<?php echo e(route('meetings.create', ['room_id' => $room->id])); ?>"
                           class="btn btn-modern btn-modern-primary <?php echo e($room->is_occupied || !$room->is_active ? 'disabled' : ''); ?>">
                            <i class="bi bi-plus-circle me-1"></i>
                            Planifier une réunion
                        </a>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $room)): ?>
                        <a href="<?php echo e(route('rooms.edit', $room)); ?>"
                           class="btn btn-modern btn-modern-secondary">
                            <i class="bi bi-pencil me-1"></i>
                            Modifier la salle
                        </a>

                        <form action="<?php echo e(route('rooms.toggle-status', $room)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit"
                                    class="btn btn-modern btn-modern-secondary w-100">
                                <i class="bi bi-<?php echo e($room->is_active ? 'pause-circle' : 'play-circle'); ?> me-1"></i>
                                <?php echo e($room->is_active ? 'Désactiver' : 'Activer'); ?> la salle
                            </button>
                        </form>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $room)): ?>
                        <form action="<?php echo e(route('rooms.destroy', $room)); ?>"
                              method="POST"
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette salle ?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-modern btn-modern-danger w-100">
                                <i class="bi bi-trash me-1"></i>
                                Supprimer la salle
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views/rooms/show.blade.php ENDPATH**/ ?>