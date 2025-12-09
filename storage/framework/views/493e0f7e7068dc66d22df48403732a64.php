<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 fw-bold text-dark">Gestion des salles de réunions</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>" class="text-decoration-none">Accueil</a></li>
                    <li class="breadcrumb-item active text-muted">Salles de réunions</li>
                </ol>
            </nav>
        </div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Room::class)): ?>
            <a href="<?php echo e(route('rooms.create')); ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Nouvelle salle
            </a>
        <?php endif; ?>
    </div>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i><?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <a href="<?php echo e(route('rooms.index', ['filter' => 'all'])); ?>"
                   class="btn <?php echo e(($filter ?? 'all') === 'all' ? 'btn-primary' : 'btn-outline-secondary'); ?>">
                    <i class="bi bi-grid-3x3-gap me-1"></i>
                    Toutes les salles
                    <span class="badge bg-white text-primary ms-1"><?php echo e($stats['total'] ?? 0); ?></span>
                </a>
                <a href="<?php echo e(route('rooms.index', ['filter' => 'available'])); ?>"
                   class="btn <?php echo e(($filter ?? '') === 'available' ? 'btn-success' : 'btn-outline-success'); ?>">
                    <i class="bi bi-check-circle me-1"></i>
                    Salles disponibles
                    <span class="badge bg-white text-success ms-1"><?php echo e($stats['available'] ?? 0); ?></span>
                </a>
                <a href="<?php echo e(route('rooms.index', ['filter' => 'occupied'])); ?>"
                   class="btn <?php echo e(($filter ?? '') === 'occupied' ? 'btn-danger' : 'btn-outline-danger'); ?>">
                    <i class="bi bi-clock-fill me-1"></i>
                    Salles occupées
                    <span class="badge bg-white text-danger ms-1"><?php echo e($stats['occupied'] ?? 0); ?></span>
                </a>

                
                <div class="ms-auto">
                    <form action="<?php echo e(route('rooms.index')); ?>" method="GET" class="d-flex gap-2">
                        <input type="hidden" name="filter" value="<?php echo e($filter ?? 'all'); ?>">
                        <div class="input-group" style="width: 250px;">
                            <input type="text" 
                                   name="q" 
                                   class="form-control" 
                                   placeholder="Rechercher une salle..." 
                                   value="<?php echo e($filters['q'] ?? ''); ?>">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row g-4">
        <?php $__empty_1 = true; $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $isOccupied = $room->is_occupied;
                $currentMeeting = $room->current_meeting;
                $participantsCount = 0;
                
                if ($currentMeeting) {
                    // Compter les participants via les délégations
                    $participantsCount = $currentMeeting->delegations?->sum(function($d) {
                        return $d->members?->count() ?? 0;
                    }) ?? 0;
                }
                
                $capacityRatio = $participantsCount > 0 && $room->capacity > 0 
                    ? min(100, round($participantsCount * 100 / $room->capacity))
                    : 0;
            ?>
            
            <div class="col-lg-6 col-xl-6">
                <div class="card border-0 shadow-sm h-100 room-card">
                    <div class="card-body p-0">
                        <div class="row g-0">
                            
                            <div class="col-md-5 position-relative">
                                <div class="room-image-wrapper">
                                    <?php if($room->image): ?>
                                        <img src="<?php echo e($room->image_url); ?>" 
                                             alt="<?php echo e($room->name); ?>" 
                                             class="room-image">
                                    <?php else: ?>
                                        <div class="room-image-placeholder">
                                            <i class="bi bi-door-open fs-1 text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    
                                    <div class="position-absolute top-0 start-0 m-2">
                                        <?php if($isOccupied): ?>
                                            <span class="badge bg-danger px-3 py-2 rounded-pill">
                                                <i class="bi bi-clock-fill me-1"></i>Occupée
                                            </span>
                                        <?php elseif(!$room->is_active): ?>
                                            <span class="badge bg-secondary px-3 py-2 rounded-pill">
                                                <i class="bi bi-pause-circle me-1"></i>Inactive
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-success px-3 py-2 rounded-pill">
                                                <i class="bi bi-check-circle me-1"></i>Disponible
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            
                            <div class="col-md-7">
                                <div class="p-3 h-100 d-flex flex-column">
                                    
                                    <div class="mb-3">
                                        <h5 class="card-title fw-bold mb-1 text-dark"><?php echo e($room->name); ?></h5>
                                        <?php if($room->location): ?>
                                            <p class="text-muted small mb-0">
                                                <i class="bi bi-geo-alt me-1"></i><?php echo e($room->location); ?>

                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    
                                    
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="small text-muted">Capacité</span>
                                            <span class="small">
                                                <?php if($isOccupied && $participantsCount > 0): ?>
                                                    <span class="fw-semibold text-primary"><?php echo e($participantsCount); ?></span>
                                                    <span class="text-muted">/</span>
                                                <?php endif; ?>
                                                <span class="fw-semibold"><?php echo e($room->capacity); ?></span>
                                            </span>
                                        </div>
                                        <div class="progress" style="height: 8px; border-radius: 4px;">
                                            <div class="progress-bar <?php echo e($capacityRatio >= 90 ? 'bg-danger' : ($capacityRatio >= 70 ? 'bg-warning' : 'bg-primary')); ?>" 
                                                 role="progressbar" 
                                                 style="width: <?php echo e($capacityRatio); ?>%;"
                                                 aria-valuenow="<?php echo e($capacityRatio); ?>" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="mb-3 flex-grow-1">
                                        <div class="small text-muted mb-2">Équipements présent dans la salle</div>
                                        <div class="d-flex flex-wrap gap-1">
                                            <?php if($room->equipments && count($room->equipments) > 0): ?>
                                                <?php $__currentLoopData = $room->equipments_with_labels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $equip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span class="badge bg-light text-dark border">
                                                        <?php echo e($equip['label']); ?>

                                                    </span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php else: ?>
                                                <span class="text-muted small fst-italic">Aucun équipement renseigné</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    
                                    <hr class="my-2">
                                    
                                    
                                    <?php if($isOccupied && $currentMeeting): ?>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="flex-grow-1 me-2">
                                                <a href="<?php echo e(route('meetings.show', $currentMeeting)); ?>" 
                                                   class="text-decoration-none">
                                                    <h6 class="mb-1 text-danger fw-semibold">
                                                        <?php echo e(Str::limit($currentMeeting->title, 35)); ?>

                                                    </h6>
                                                </a>
                                                <div class="d-flex align-items-center text-muted small">
                                                    <span class="me-3">
                                                        <i class="bi bi-clock me-1"></i>
                                                        <?php echo e($currentMeeting->start_at?->format('H:i')); ?>

                                                        <?php if($currentMeeting->end_at): ?>
                                                            - <?php echo e($currentMeeting->end_at->format('H:i')); ?>

                                                        <?php elseif($currentMeeting->duration_minutes): ?>
                                                            - <?php echo e($currentMeeting->start_at->copy()->addMinutes($currentMeeting->duration_minutes)->format('H:i')); ?>

                                                        <?php endif; ?>
                                                    </span>
                                                    <span>
                                                        <i class="bi bi-people me-1"></i>
                                                        <?php echo e($participantsCount); ?> Personne<?php echo e($participantsCount > 1 ? 's' : ''); ?>

                                                    </span>
                                                </div>
                                            </div>
                                            <a href="<?php echo e(route('meetings.show', $currentMeeting)); ?>" 
                                               class="btn btn-danger btn-sm">
                                                Voir Plus
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center py-2">
                                            <p class="text-muted small mb-2">
                                                <i class="bi bi-calendar-x me-1"></i>
                                                Aucune réunion prévue dans cette salle
                                            </p>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Meeting::class)): ?>
                                                <a href="<?php echo e(route('meetings.create', ['room_id' => $room->id])); ?>" 
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-plus-circle me-1"></i>
                                                    Organiser une réunion
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="card-footer bg-transparent border-0 py-2 px-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="bi bi-tag me-1"></i><?php echo e($room->code ?? 'N/A'); ?>

                            </small>
                            <div class="btn-group btn-group-sm">
                                <a href="<?php echo e(route('rooms.show', $room)); ?>" 
                                   class="btn btn-outline-secondary" 
                                   title="Voir les détails">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $room)): ?>
                                    <a href="<?php echo e(route('rooms.edit', $room)); ?>" 
                                       class="btn btn-outline-secondary" 
                                       title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $room)): ?>
                                    <form action="<?php echo e(route('rooms.destroy', $room)); ?>" 
                                          method="POST" 
                                          class="d-inline"
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
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="mb-3">
                            <i class="bi bi-door-open text-muted" style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="text-muted mb-2">Aucune salle enregistrée</h5>
                        <p class="text-muted mb-3">Commencez par créer votre première salle de réunion.</p>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Room::class)): ?>
                            <a href="<?php echo e(route('rooms.create')); ?>" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i> Créer une salle
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    
    <?php if($rooms->hasPages()): ?>
        <div class="d-flex justify-content-center mt-4">
            <?php echo e($rooms->links()); ?>

        </div>
    <?php endif; ?>
</div>

<?php $__env->startPush('styles'); ?>
<style>
    .room-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        overflow: hidden;
    }
    
    .room-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    
    .room-image-wrapper {
        height: 100%;
        min-height: 200px;
        overflow: hidden;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
    
    .room-image {
        width: 100%;
        height: 100%;
        min-height: 200px;
        object-fit: cover;
    }
    
    .room-image-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        min-height: 200px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
    
    .badge {
        font-weight: 500;
    }
    
    .progress {
        background-color: #e9ecef;
    }
    
    @media (max-width: 767px) {
        .room-image-wrapper {
            min-height: 150px;
        }
        
        .room-image,
        .room-image-placeholder {
            min-height: 150px;
        }
    }
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views/rooms/index.blade.php ENDPATH**/ ?>