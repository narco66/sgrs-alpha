<?php $__env->startSection('title', 'Participants'); ?>

<?php $__env->startSection('content'); ?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Accueil</a></li>
        <li class="breadcrumb-item active">Participants</li>
    </ol>
</nav>


<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="page-title mb-1">Participants</h3>
        <p class="text-muted mb-0 small">Accueil / Participants</p>
    </div>
    <a href="<?php echo e(route('meetings.index')); ?>" class="btn btn-modern btn-modern-secondary">
        <i class="bi bi-calendar3"></i>
        Gérer les participants par réunion
    </a>
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


<div class="modern-card mb-3">
    <div class="modern-card-header">
        <h5 class="modern-card-title">
            <i class="bi bi-funnel"></i>
            Filtres
        </h5>
    </div>
    <div class="modern-card-body">
        <form method="GET" action="<?php echo e(route('participants.index')); ?>" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Recherche</label>
                <input type="text" name="q" class="form-control" value="<?php echo e($search ?? ''); ?>" placeholder="Nom, email, service">
            </div>
            <div class="col-md-3">
                <label class="form-label">Réunion</label>
                <select name="meeting_id" class="form-select">
                    <option value="">Toutes</option>
                    <?php $__currentLoopData = $meetings ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meeting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($meeting->id); ?>" <?php if(($meetingId ?? '') == $meeting->id): echo 'selected'; endif; ?>>
                            <?php echo e($meeting->title); ?> - <?php echo e($meeting->start_at?->format('d/m')); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Statut</label>
                <select name="status" class="form-select">
                    <option value="all" <?php if(($status ?? 'all') === 'all'): echo 'selected'; endif; ?>>Tous</option>
                    <option value="active" <?php if(($status ?? '') === 'active'): echo 'selected'; endif; ?>>Actifs</option>
                    <option value="inactive" <?php if(($status ?? '') === 'inactive'): echo 'selected'; endif; ?>>Inactifs</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-modern btn-modern-primary w-100" title="Appliquer les filtres">
                    <i class="bi bi-search"></i>
                </button>
                <a href="<?php echo e(route('participants.index')); ?>" class="btn btn-modern btn-modern-secondary" title="Réinitialiser">
                    <i class="bi bi-arrow-repeat"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<div class="modern-card">
    <div class="modern-card-header">
        <h5 class="modern-card-title">
            <i class="bi bi-people"></i>
            Liste des participants
        </h5>
        <span class="badge-modern badge-modern-primary">
            <?php echo e($participants->total()); ?> participant<?php echo e($participants->total() > 1 ? 's' : ''); ?>

        </span>
    </div>
    <div class="modern-card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Participant</th>
                        <th>Email</th>
                        <th>Service</th>
                        <th>Statut</th>
                        <th>Réunions</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $participants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $participant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $displayName = $participant->name
                            ?: trim(($participant->first_name ?? '') . ' ' . ($participant->last_name ?? ''));
                    ?>
                    <tr>
                        <td class="fw-semibold"><?php echo e($displayName ?: 'Utilisateur'); ?></td>
                        <td><?php echo e($participant->email ?? 'N/A'); ?></td>
                        <td><?php echo e($participant->service ?? 'Non renseigné'); ?></td>
                        <td>
                            <?php if($participant->is_active): ?>
                                <span class="badge-modern badge-modern-success">Actif</span>
                            <?php else: ?>
                                <span class="badge-modern badge-modern-secondary">Inactif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark"><?php echo e($participant->meetings_count ?? 0); ?></span>
                            <?php if(($participant->meetingParticipations ?? null)?->isNotEmpty()): ?>
                                <div class="small text-muted mt-1">
                                    <?php $__currentLoopData = ($participant->meetingParticipations->take(3) ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div>- <?php echo e($mp->meeting?->title ?? 'Réunion'); ?> (<?php echo e($mp->meeting?->start_at?->format('d/m')); ?>)</div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(($participant->meetingParticipations->count() ?? 0) > 3): ?>
                                        <div class="text-muted">+ <?php echo e($participant->meetingParticipations->count() - 3); ?> autres...</div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <div class="table-actions">
                                <a href="<?php echo e(route('users.show', $participant)); ?>"
                                   class="btn btn-sm btn-outline-secondary"
                                   data-bs-toggle="tooltip"
                                   title="Voir la fiche utilisateur">
                                    <i class="bi bi-person"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi bi-inbox empty-state-icon"></i>
                                <div class="empty-state-title">Aucun participant</div>
                                <div class="empty-state-text">Aucun participant relié à une réunion pour le moment.</div>
                                <a href="<?php echo e(route('participants.index')); ?>" class="btn btn-modern btn-modern-secondary mt-3">Réinitialiser les filtres</a>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($participants->hasPages()): ?>
            <div class="modern-card-footer">
                <div class="small text-muted">
                    Affichage de <?php echo e($participants->firstItem()); ?> à <?php echo e($participants->lastItem()); ?>

                    sur <?php echo e($participants->total()); ?> participant<?php echo e($participants->total() > 1 ? 's' : ''); ?>

                </div>
                <div class="pagination-modern">
                    <?php echo e($participants->appends(request()->query())->links()); ?>

                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views/participants/index.blade.php ENDPATH**/ ?>