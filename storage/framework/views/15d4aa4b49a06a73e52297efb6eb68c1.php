<?php $__env->startSection('title', 'Réunions statutaires'); ?>

<?php $__env->startSection('content'); ?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Accueil</a></li>
        <li class="breadcrumb-item active">Réunions</li>
    </ol>
</nav>


<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="page-title mb-1">Réunions statutaires</h3>
        <p class="text-muted mb-0 small">Accueil / Réunions</p>
    </div>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\Meeting::class)): ?>
        <a href="<?php echo e(route('meetings.create')); ?>" class="btn btn-modern btn-modern-primary">
            <i class="bi bi-plus-circle"></i> Nouvelle réunion
        </a>
    <?php endif; ?>
</div>

<?php echo $__env->make('partials.alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<?php
    $totalDelegations = $meetings->sum(function ($meeting) {
        return $meeting->delegations()->count();
    });
    $totalMembers = $meetings->sum(function ($meeting) {
        return $meeting->delegations()->withCount('members')->get()->sum('members_count');
    });
    $totalMeetings = $meetings->total();
?>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="modern-card h-100">
            <div class="modern-card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="kpi-icon" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);">
                            <i class="bi bi-calendar-event text-primary"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="kpi-value mb-0"><?php echo e($totalMeetings); ?></h5>
                        <small class="kpi-label">Réunion<?php echo e($totalMeetings > 1 ? 's' : ''); ?> au total</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="modern-card h-100">
            <div class="modern-card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="kpi-icon" style="background: linear-gradient(135deg, rgba(67, 233, 123, 0.1) 0%, rgba(56, 249, 215, 0.1) 100%);">
                            <i class="bi bi-people text-success"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="kpi-value mb-0"><?php echo e($totalDelegations); ?></h5>
                        <small class="kpi-label">Délégation<?php echo e($totalDelegations > 1 ? 's' : ''); ?> total</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="modern-card h-100">
            <div class="modern-card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="kpi-icon" style="background: linear-gradient(135deg, rgba(79, 172, 254, 0.1) 0%, rgba(0, 242, 254, 0.1) 100%);">
                            <i class="bi bi-check-circle text-info"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="kpi-value mb-0"><?php echo e($meetings->where('status', 'terminee')->count()); ?></h5>
                        <small class="kpi-label">Réunion<?php echo e($meetings->where('status', 'terminee')->count() > 1 ? 's' : ''); ?> terminée<?php echo e($meetings->where('status', 'terminee')->count() > 1 ? 's' : ''); ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="modern-card h-100">
            <div class="modern-card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="kpi-icon" style="background: linear-gradient(135deg, rgba(240, 147, 251, 0.1) 0%, rgba(245, 87, 108, 0.1) 100%);">
                            <i class="bi bi-clock-history text-warning"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="kpi-value mb-0"><?php echo e($meetings->where('status', 'en_cours')->count()); ?></h5>
                        <small class="kpi-label">En cours</small>
                    </div>
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
        <?php if(collect($filters)->filter()->isNotEmpty()): ?>
            <a href="<?php echo e(route('meetings.index')); ?>" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-x-lg me-1"></i> Réinitialiser
            </a>
        <?php endif; ?>
    </div>
    
    <form method="GET" action="<?php echo e(route('meetings.index')); ?>" class="row g-3">
        <div class="col-md-3">
            <label class="form-label">
                <i class="bi bi-search"></i>
                Recherche
            </label>
            <input type="text" 
                   name="q" 
                   class="form-control" 
                   value="<?php echo e($filters['q'] ?? ''); ?>" 
                   placeholder="Titre, description, ordre du jour...">
        </div>
        
        <div class="col-md-2">
            <label class="form-label">
                <i class="bi bi-diagram-3"></i>
                Type
            </label>
            <select name="meeting_type_id" class="form-select">
                <option value="">Tous les types</option>
                <?php $__currentLoopData = $meetingTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($type->id); ?>" 
                            <?php if(($filters['meeting_type_id'] ?? '') == $type->id): echo 'selected'; endif; ?>>
                        <?php echo e($type->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        
        <div class="col-md-2">
            <label class="form-label">
                <i class="bi bi-people"></i>
                Comité
            </label>
            <select name="committee_id" class="form-select">
                <option value="">Tous les comités</option>
                <?php $__currentLoopData = $committees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $committee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($committee->id); ?>" 
                            <?php if(($filters['committee_id'] ?? '') == $committee->id): echo 'selected'; endif; ?>>
                        <?php echo e($committee->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        
        <div class="col-md-2">
            <label class="form-label">
                <i class="bi bi-info-circle"></i>
                Statut
            </label>
            <select name="status" class="form-select">
                <option value="">Tous les statuts</option>
                <option value="brouillon" <?php if(($filters['status'] ?? '') == 'brouillon'): echo 'selected'; endif; ?>>Brouillon</option>
                <option value="planifiee" <?php if(($filters['status'] ?? '') == 'planifiee'): echo 'selected'; endif; ?>>Planifiée</option>
                <option value="en_preparation" <?php if(($filters['status'] ?? '') == 'en_preparation'): echo 'selected'; endif; ?>>En préparation</option>
                <option value="en_cours" <?php if(($filters['status'] ?? '') == 'en_cours'): echo 'selected'; endif; ?>>En cours</option>
                <option value="terminee" <?php if(($filters['status'] ?? '') == 'terminee'): echo 'selected'; endif; ?>>Terminée</option>
                <option value="annulee" <?php if(($filters['status'] ?? '') == 'annulee'): echo 'selected'; endif; ?>>Annulée</option>
            </select>
        </div>
        
        <div class="col-md-3">
            <label class="form-label">
                <i class="bi bi-calendar-range"></i>
                Période
            </label>
            <div class="d-flex gap-2">
                <input type="date" 
                       name="date_from" 
                       class="form-control" 
                       value="<?php echo e($filters['date_from'] ?? ''); ?>" 
                       placeholder="Du">
                <input type="date" 
                       name="date_to" 
                       class="form-control" 
                       value="<?php echo e($filters['date_to'] ?? ''); ?>" 
                       placeholder="Au">
            </div>
        </div>
        
        <div class="col-12">
            <div class="d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-modern btn-modern-primary">
                    <i class="bi bi-search"></i> Appliquer les filtres
                </button>
                <?php if(collect($filters)->filter()->isNotEmpty()): ?>
                    <a href="<?php echo e(route('meetings.index')); ?>" class="btn btn-modern btn-modern-secondary">
                        <i class="bi bi-x-circle"></i> Réinitialiser
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>


<div class="modern-card">
    <div class="modern-card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="modern-card-title">
                <i class="bi bi-list-ul"></i>
                Liste des réunions
            </h5>
            <span class="badge-modern badge-modern-primary">
                <?php echo e($meetings->total()); ?> résultat<?php echo e($meetings->total() > 1 ? 's' : ''); ?>

            </span>
        </div>
    </div>
    <div class="modern-card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="sortable" style="width: 30%;">
                            Titre
                            <i class="bi bi-arrow-down-up"></i>
                        </th>
                        <th class="sortable" style="width: 12%;">
                            Type
                            <i class="bi bi-arrow-down-up"></i>
                        </th>
                        <th class="sortable" style="width: 10%;">
                            Statut
                            <i class="bi bi-arrow-down-up"></i>
                        </th>
                        <th class="sortable" style="width: 10%;">
                            Date & Heure
                            <i class="bi bi-arrow-down-up"></i>
                        </th>
                        <th style="width: 8%;">Durée</th>
                        <th style="width: 8%;">Salle</th>
                        <th style="width: 8%;">Participants</th>
                        <th style="width: 14%;" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $meetings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meeting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-start">
                                    <div class="flex-grow-1">
                                        <a href="<?php echo e(route('meetings.show', $meeting)); ?>" 
                                           class="text-decoration-none fw-semibold text-dark">
                                            <?php echo e($meeting->title); ?>

                                        </a>
                                        <?php if($meeting->committee): ?>
                                            <div class="small text-muted mt-1">
                                                <i class="bi bi-people me-1"></i>
                                                <?php echo e($meeting->committee->name); ?>

                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <?php if($meeting->meetingType): ?>
                                    <span class="badge-modern badge-modern-info">
                                        <?php echo e($meeting->meetingType->name); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="text-muted small">—</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php
                                    $statusConfig = match($meeting->status) {
                                        'brouillon' => ['class' => 'badge-modern-secondary', 'label' => 'Brouillon'],
                                        'planifiee' => ['class' => 'badge-modern-primary', 'label' => 'Planifiée'],
                                        'en_preparation' => ['class' => 'badge-modern-info', 'label' => 'En préparation'],
                                        'en_cours' => ['class' => 'badge-modern-warning', 'label' => 'En cours'],
                                        'terminee' => ['class' => 'badge-modern-success', 'label' => 'Clôturée'],
                                        'annulee' => ['class' => 'badge-modern-danger', 'label' => 'Annulée'],
                                        'scheduled' => ['class' => 'badge-modern-primary', 'label' => 'Planifiée'],
                                        'ongoing' => ['class' => 'badge-modern-warning', 'label' => 'En cours'],
                                        'completed' => ['class' => 'badge-modern-success', 'label' => 'Clôturée'],
                                        default => ['class' => 'badge-modern-secondary', 'label' => ucfirst($meeting->status ?? 'N/A')],
                                    };
                                ?>
                                <span class="badge-modern <?php echo e($statusConfig['class']); ?>">
                                    <?php echo e($statusConfig['label']); ?>

                                </span>
                            </td>

                            <td>
                                <div class="small">
                                    <div class="fw-semibold">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        <?php echo e($meeting->start_at?->format('d/m/Y')); ?>

                                    </div>
                                    <div class="text-muted">
                                        <i class="bi bi-clock me-1"></i>
                                        <?php echo e($meeting->start_at?->format('H:i')); ?>

                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="badge-modern badge-modern-secondary">
                                    <?php echo e($meeting->duration_minutes ?? 0); ?> min
                                </span>
                            </td>

                            <td>
                                <?php if($meeting->room && is_object($meeting->room)): ?>
                                    <span class="small">
                                        <i class="bi bi-geo-alt me-1"></i>
                                        <?php echo e(Str::limit($meeting->room->name, 20)); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="text-muted small">—</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <div class="d-flex flex-column">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-building text-muted me-1"></i>
                                        <span class="fw-semibold"><?php echo e($meeting->delegations_count ?? 0); ?></span>
                                    </div>
                                    <?php
                                        $membersCount = 0;
                                        if ($meeting->relationLoaded('delegations')) {
                                            $membersCount = $meeting->delegations->sum(function($d) {
                                                return $d->members_count ?? 0;
                                            });
                                        }
                                    ?>
                                    <?php if($membersCount > 0): ?>
                                        <small class="text-muted"><?php echo e($membersCount); ?> membre<?php echo e($membersCount > 1 ? 's' : ''); ?></small>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <td class="text-end">
                                <div class="d-flex align-items-center justify-content-end gap-2">
                                    <a href="<?php echo e(route('meetings.show', $meeting)); ?>"
                                       class="btn btn-sm btn-outline-primary"
                                       data-bs-toggle="tooltip"
                                       title="Voir les détails">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $meeting)): ?>
                                        <a href="<?php echo e(route('meetings.edit', $meeting)); ?>"
                                           class="btn btn-sm btn-outline-secondary"
                                           data-bs-toggle="tooltip"
                                           title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $meeting)): ?>
                                        <form action="<?php echo e(route('meetings.destroy', $meeting)); ?>" 
                                              method="POST" 
                                              class="d-inline m-0"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette réunion ?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="tooltip"
                                                    title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="bi bi-inbox empty-state-icon"></i>
                                    <div class="empty-state-title">Aucune réunion trouvée</div>
                                    <div class="empty-state-text">
                                        <?php if(collect($filters)->filter()->isNotEmpty()): ?>
                                            Aucune réunion ne correspond à vos critères de recherche.
                                        <?php else: ?>
                                            Aucune réunion enregistrée pour le moment.
                                        <?php endif; ?>
                                    </div>
                                    <?php if(collect($filters)->filter()->isNotEmpty()): ?>
                                        <a href="<?php echo e(route('meetings.index')); ?>" class="btn btn-modern btn-modern-primary mt-3">
                                            <i class="bi bi-x-circle"></i> Réinitialiser les filtres
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<?php if($meetings->hasPages()): ?>
    <div class="modern-card mt-4">
        <div class="modern-card-footer">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="small text-muted">
                    <i class="bi bi-info-circle me-1"></i>
                    Affichage de <strong><?php echo e($meetings->firstItem()); ?></strong> à <strong><?php echo e($meetings->lastItem()); ?></strong> 
                    sur <strong><?php echo e($meetings->total()); ?></strong> réunion<?php echo e($meetings->total() > 1 ? 's' : ''); ?>

                </div>
                <div class="pagination-modern">
                    <?php echo e($meetings->appends(request()->query())->links()); ?>

                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Alignement des boutons d'action dans le tableau */
    .table td .d-flex {
        min-height: 38px;
    }

    .table td form {
        margin: 0;
        display: inline-flex;
        align-items: center;
    }

    .table td .btn {
        min-width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.375rem 0.75rem;
    }

    /* Pagination améliorée */
    .pagination-modern {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .pagination-modern .pagination {
        margin: 0;
        gap: 0.25rem;
    }

    .pagination-modern .page-link {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        color: #64748b;
        padding: 0.5rem 0.75rem;
        transition: all 0.2s ease;
    }

    .pagination-modern .page-link:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: #667eea;
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .pagination-modern .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: #667eea;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .pagination-modern .page-item.disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views/meetings/index.blade.php ENDPATH**/ ?>