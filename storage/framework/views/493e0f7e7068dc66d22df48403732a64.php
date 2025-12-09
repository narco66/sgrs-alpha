<?php $__env->startSection('title', 'Salles de réunion'); ?>

<?php $__env->startSection('content'); ?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('dashboard')); ?>">Accueil</a>
        </li>
        <li class="breadcrumb-item active">Salles de réunion</li>
    </ol>
</nav>


<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="page-title mb-1">Salles de réunion</h3>
        <p class="text-muted mb-0 small">Configuration des réunions / Salles</p>
    </div>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\Room::class)): ?>
        <a href="<?php echo e(route('rooms.create')); ?>" class="btn btn-modern btn-modern-primary">
            <i class="bi bi-plus-circle"></i>
            Nouvelle salle
        </a>
    <?php endif; ?>
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
<?php elseif(session('error')): ?>
    <?php if (isset($component)) { $__componentOriginal682e217f64e93fddc2bc39228ca2c21e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal682e217f64e93fddc2bc39228ca2c21e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modern-alert','data' => ['type' => 'danger','dismissible' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modern-alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'danger','dismissible' => true]); ?>
        <?php echo e(session('error')); ?>

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


<?php
    $filter = $filter ?? 'all';
?>
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="modern-card h-100">
            <div class="modern-card-body d-flex align-items-center">
                <div class="kpi-icon" style="background: linear-gradient(135deg, rgba(102,126,234,0.08) 0%, rgba(118,75,162,0.12) 100%);">
                    <i class="bi bi-building text-primary"></i>
                </div>
                <div class="ms-3">
                    <div class="kpi-value mb-0"><?php echo e($stats['total'] ?? 0); ?></div>
                    <div class="kpi-label">Salle<?php echo e(($stats['total'] ?? 0) > 1 ? 's' : ''); ?> au total</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="modern-card h-100">
            <div class="modern-card-body d-flex align-items-center">
                <div class="kpi-icon" style="background: linear-gradient(135deg, rgba(67,233,123,0.08) 0%, rgba(56,249,215,0.12) 100%);">
                    <i class="bi bi-check-circle text-success"></i>
                </div>
                <div class="ms-3">
                    <div class="kpi-value mb-0"><?php echo e($stats['available'] ?? 0); ?></div>
                    <div class="kpi-label">Salles disponibles</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="modern-card h-100">
            <div class="modern-card-body d-flex align-items-center">
                <div class="kpi-icon" style="background: linear-gradient(135deg, rgba(240,147,251,0.08) 0%, rgba(245,87,108,0.12) 100%);">
                    <i class="bi bi-clock-history text-warning"></i>
                </div>
                <div class="ms-3">
                    <div class="kpi-value mb-0"><?php echo e($stats['occupied'] ?? 0); ?></div>
                    <div class="kpi-label">Salles occupées</div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modern-filters mb-4">
    <div class="modern-filters-header">
        <h5 class="modern-filters-title">
            <i class="bi bi-funnel"></i>
            Filtres de recherche
        </h5>
        <?php if(($filters['q'] ?? null) || ($filters['capacity'] ?? null) || $filter !== 'all'): ?>
            <a href="<?php echo e(route('rooms.index')); ?>" class="btn btn-sm btn-modern btn-modern-secondary">
                <i class="bi bi-x-lg me-1"></i>
                Réinitialiser
            </a>
        <?php endif; ?>
    </div>

    <form method="GET" action="<?php echo e(route('rooms.index')); ?>" class="row g-3 align-items-end">
        
        <div class="col-md-4">
            <label class="form-label">
                <i class="bi bi-search"></i>
                Recherche
            </label>
            <input type="text"
                   name="q"
                   class="form-control"
                   value="<?php echo e($filters['q'] ?? ''); ?>"
                   placeholder="Nom, code, localisation...">
        </div>

        
        <div class="col-md-3">
            <label class="form-label">
                <i class="bi bi-people"></i>
                Capacité minimale
            </label>
            <div class="input-group">
                <input type="number"
                       name="capacity"
                       class="form-control"
                       value="<?php echo e($filters['capacity'] ?? ''); ?>"
                       min="1"
                       placeholder="Ex : 20">
                <span class="input-group-text">pers.</span>
            </div>
        </div>

        
        <div class="col-md-5">
            <label class="form-label">
                <i class="bi bi-circle-half"></i>
                Disponibilité
            </label>
            <div class="btn-group w-100" role="group">
                <button type="submit"
                        name="filter"
                        value="all"
                        class="btn btn-modern <?php echo e($filter === 'all' ? 'btn-modern-primary' : 'btn-modern-secondary'); ?>">
                    <i class="bi bi-grid-3x3-gap me-1"></i>
                    Toutes
                </button>
                <button type="submit"
                        name="filter"
                        value="available"
                        class="btn btn-modern <?php echo e($filter === 'available' ? 'btn-modern-primary' : 'btn-modern-secondary'); ?>">
                    <i class="bi bi-check-circle me-1"></i>
                    Disponibles
                </button>
                <button type="submit"
                        name="filter"
                        value="occupied"
                        class="btn btn-modern <?php echo e($filter === 'occupied' ? 'btn-modern-primary' : 'btn-modern-secondary'); ?>">
                    <i class="bi bi-clock-fill me-1"></i>
                    Occupées
                </button>
            </div>
        </div>

        <div class="col-12 d-flex justify-content-end">
            <button type="submit" class="btn btn-modern btn-modern-primary">
                <i class="bi bi-search me-1"></i>
                Appliquer les filtres
            </button>
        </div>
    </form>
</div>


<?php if($rooms->count() > 0): ?>
    <div class="row g-4">
        <?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $isOccupied      = $room->is_occupied;
                $currentMeeting  = $room->current_meeting;
                $participants    = 0;

                if ($currentMeeting) {
                    $participants = $currentMeeting->delegations?->sum(function ($d) {
                        return $d->members?->count() ?? 0;
                    }) ?? 0;
                }

                $capacityRatio = $participants > 0 && $room->capacity > 0
                    ? min(100, round($participants * 100 / $room->capacity))
                    : 0;
            ?>

            <div class="col-xl-4 col-lg-6">
                <div class="modern-card room-card h-100">
                    <div class="modern-card-body">
                        <div class="d-flex gap-3">
                            
                            <div class="room-thumb flex-shrink-0">
                                <a href="<?php echo e(route('rooms.show', $room)); ?>" class="d-block h-100">
                                    <?php if($room->image): ?>
                                        <img src="<?php echo e($room->image_url); ?>"
                                             alt="<?php echo e($room->name); ?>"
                                             class="room-thumb-image">
                                    <?php else: ?>
                                        <div class="room-thumb-placeholder">
                                            <i class="bi bi-door-open"></i>
                                        </div>
                                    <?php endif; ?>
                                </a>

                                
                                <div class="room-thumb-badge">
                                    <?php if($isOccupied): ?>
                                        <span class="badge-modern badge-modern-danger">
                                            <i class="bi bi-clock-fill me-1"></i>Occupée
                                        </span>
                                    <?php elseif(!$room->is_active): ?>
                                        <span class="badge-modern badge-modern-secondary">
                                            <i class="bi bi-pause-circle me-1"></i>Inactive
                                        </span>
                                    <?php else: ?>
                                        <span class="badge-modern badge-modern-success">
                                            <i class="bi bi-check-circle me-1"></i>Disponible
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            
                            <div class="flex-grow-1 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h5 class="mb-1 fw-semibold">
                                            <a href="<?php echo e(route('rooms.show', $room)); ?>" class="text-decoration-none text-dark">
                                                <?php echo e($room->name); ?>

                                            </a>
                                        </h5>
                                        <?php if($room->location): ?>
                                            <div class="small text-muted">
                                                <i class="bi bi-geo-alt me-1"></i><?php echo e($room->location); ?>

                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <span class="badge bg-light text-dark small">
                                        <i class="bi bi-tag me-1"></i><?php echo e($room->code ?? 'N/A'); ?>

                                    </span>
                                </div>

                                
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span class="text-muted">Capacité</span>
                                        <span>
                                            <?php if($participants > 0): ?>
                                                <strong class="text-primary"><?php echo e($participants); ?></strong>
                                                <span class="text-muted">/</span>
                                            <?php endif; ?>
                                            <strong><?php echo e($room->capacity); ?></strong> pers.
                                        </span>
                                    </div>
                                    <div class="progress room-progress">
                                        <div class="progress-bar
                                                    <?php echo e($capacityRatio >= 90 ? 'bg-danger' : ($capacityRatio >= 70 ? 'bg-warning' : 'bg-primary')); ?>"
                                             role="progressbar"
                                             style="width: <?php echo e($capacityRatio); ?>%;"
                                             aria-valuenow="<?php echo e($capacityRatio); ?>"
                                             aria-valuemin="0"
                                             aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>

                                
                                <div class="mb-2">
                                    <?php if($room->equipments && count($room->equipments) > 0): ?>
                                        <div class="d-flex flex-wrap gap-1 small">
                                            <?php $__currentLoopData = collect($room->equipments_with_labels)->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $equip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="badge bg-light text-dark border">
                                                    <?php echo e($equip['label']); ?>

                                                </span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php if(count($room->equipments_with_labels) > 4): ?>
                                                <span class="badge bg-light text-dark border">
                                                    +<?php echo e(count($room->equipments_with_labels) - 4); ?> autres
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="small text-muted fst-italic">
                                            Aucun équipement renseigné
                                        </span>
                                    <?php endif; ?>
                                </div>

                                
                                <div class="mt-auto pt-2 border-top small">
                                    <?php if($isOccupied && $currentMeeting): ?>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="me-2">
                                                <a href="<?php echo e(route('meetings.show', $currentMeeting)); ?>"
                                                   class="text-decoration-none">
                                                    <div class="fw-semibold text-danger">
                                                        <?php echo e(\Illuminate\Support\Str::limit($currentMeeting->title, 40)); ?>

                                                    </div>
                                                </a>
                                                <div class="text-muted">
                                                    <i class="bi bi-clock me-1"></i>
                                                    <?php echo e($currentMeeting->start_at?->format('H:i')); ?>

                                                    <?php if($currentMeeting->end_at): ?>
                                                        – <?php echo e($currentMeeting->end_at->format('H:i')); ?>

                                                    <?php elseif($currentMeeting->duration_minutes): ?>
                                                        – <?php echo e($currentMeeting->start_at->copy()->addMinutes($currentMeeting->duration_minutes)->format('H:i')); ?>

                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <a href="<?php echo e(route('meetings.show', $currentMeeting)); ?>"
                                               class="btn btn-sm btn-outline-danger">
                                                Voir
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted">
                                                <i class="bi bi-calendar-x me-1"></i>
                                                Aucune réunion en cours
                                            </span>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\Meeting::class)): ?>
                                                <a href="<?php echo e(route('meetings.create', ['room_id' => $room->id])); ?>"
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-plus-circle me-1"></i>
                                                    Planifier
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="modern-card-footer d-flex justify-content-between align-items-center">
                        <span class="small text-muted">
                            Créée le <?php echo e($room->created_at?->format('d/m/Y') ?? 'N/A'); ?>

                        </span>
                        <div class="btn-group btn-group-sm">
                            <a href="<?php echo e(route('rooms.show', $room)); ?>"
                               class="btn btn-outline-secondary"
                               title="Voir les détails">
                                <i class="bi bi-eye"></i>
                            </a>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $room)): ?>
                                <a href="<?php echo e(route('rooms.edit', $room)); ?>"
                                   class="btn btn-outline-primary"
                                   title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $room)): ?>
                                <form action="<?php echo e(route('rooms.destroy', $room)); ?>"
                                      method="POST"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette salle ?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit"
                                            class="btn btn-outline-danger"
                                            title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <?php if($rooms->hasPages()): ?>
        <div class="modern-card mt-4">
            <div class="modern-card-footer d-flex justify-content-between align-items-center">
                <div class="small text-muted">
                    Affichage de <?php echo e($rooms->firstItem()); ?> à <?php echo e($rooms->lastItem()); ?>

                    sur <?php echo e($rooms->total()); ?> salle<?php echo e($rooms->total() > 1 ? 's' : ''); ?>

                </div>
                <div class="pagination-modern">
                    <?php echo e($rooms->appends(request()->query())->links()); ?>

                </div>
            </div>
        </div>
    <?php endif; ?>
<?php else: ?>
    <div class="modern-card">
        <div class="modern-card-body text-center py-5">
            <div class="empty-state">
                <i class="bi bi-building empty-state-icon"></i>
                <div class="empty-state-title">Aucune salle enregistrée</div>
                <div class="empty-state-text">
                    Créez votre première salle de réunion pour commencer à planifier les sessions.
                </div>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\Room::class)): ?>
                    <a href="<?php echo e(route('rooms.create')); ?>" class="btn btn-modern btn-modern-primary mt-3">
                        <i class="bi bi-plus-circle me-1"></i>
                        Créer une salle
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php $__env->startPush('styles'); ?>
<style>
    .room-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .room-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.12);
    }

    .room-thumb {
        position: relative;
        width: 120px;
        min-width: 120px;
        height: 90px;
        border-radius: 12px;
        overflow: hidden;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    }

    .room-thumb-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .room-thumb-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        font-size: 2rem;
    }

    .room-thumb-badge {
        position: absolute;
        top: 6px;
        left: 6px;
        right: 6px;
        display: flex;
        justify-content: space-between;
        pointer-events: none;
    }

    .room-progress {
        height: 8px;
        border-radius: 999px;
        background-color: #e2e8f0;
    }

    @media (max-width: 575.98px) {
        .room-thumb {
            width: 100px;
            min-width: 100px;
            height: 80px;
        }
    }
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views/rooms/index.blade.php ENDPATH**/ ?>