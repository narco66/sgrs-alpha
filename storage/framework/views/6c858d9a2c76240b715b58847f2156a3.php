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
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\Participant::class)): ?>
        <a href="<?php echo e(route('participants.create')); ?>" class="btn btn-modern btn-modern-primary">
            <i class="bi bi-person-plus"></i>
            Nouveau participant
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
                <input type="text" name="q" class="form-control" value="<?php echo e($search ?? ''); ?>" placeholder="Nom, email, institution">
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
            <div class="col-md-2">
                <label class="form-label">Statut</label>
                <select name="status" class="form-select">
                    <option value="all" <?php if(($status ?? 'all') === 'all'): echo 'selected'; endif; ?>>Tous</option>
                    <option value="active" <?php if(($status ?? '') === 'active'): echo 'selected'; endif; ?>>Actifs</option>
                    <option value="inactive" <?php if(($status ?? '') === 'inactive'): echo 'selected'; endif; ?>>Inactifs</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Type</label>
                <select name="type" class="form-select">
                    <option value="all" <?php if(($type ?? 'all') === 'all'): echo 'selected'; endif; ?>>Tous</option>
                    <option value="internal" <?php if(($type ?? '') === 'internal'): echo 'selected'; endif; ?>>Interne</option>
                    <option value="external" <?php if(($type ?? '') === 'external'): echo 'selected'; endif; ?>>Externe</option>
                </select>
            </div>
            <div class="col-md-1 d-flex gap-2">
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
                        <th>Nom</th>
                        <th>Institution</th>
                        <th>Pays</th>
                        <th>Type</th>
                        <th>Statut</th>
                        <th>Réunions</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $participants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $participant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="fw-semibold"><?php echo e($participant->full_name); ?></td>
                        <td><?php echo e($participant->institution ?? '—'); ?></td>
                        <td><?php echo e($participant->country ?? '—'); ?></td>
                        <td>
                            <?php if($participant->is_internal): ?>
                                <span class="badge-modern badge-modern-primary">Interne</span>
                            <?php else: ?>
                                <span class="badge-modern badge-modern-info">Externe</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($participant->is_active): ?>
                                <span class="badge-modern badge-modern-success">Actif</span>
                            <?php else: ?>
                                <span class="badge-modern badge-modern-secondary">Inactif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark"><?php echo e($participant->meetings_count ?? 0); ?></span>
                        </td>
                        <td class="text-end">
                            <div class="table-actions">
                                <a href="<?php echo e(route('participants.show', $participant)); ?>"
                                   class="btn btn-sm btn-outline-secondary"
                                   data-bs-toggle="tooltip"
                                   title="Voir les détails">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="<?php echo e(route('participants.edit', $participant)); ?>"
                                   class="btn btn-sm btn-outline-primary"
                                   data-bs-toggle="tooltip"
                                   title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="<?php echo e(route('participants.destroy', $participant)); ?>" method="POST" class="d-inline"
                                      onsubmit="return confirm('Confirmez-vous la suppression de ce participant ?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi bi-inbox empty-state-icon"></i>
                                <div class="empty-state-title">Aucun participant</div>
                                <div class="empty-state-text">Aucun participant enregistré pour le moment.</div>
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\participants\index.blade.php ENDPATH**/ ?>