

<?php $__env->startSection('title', 'Demandes de réunion'); ?>

<?php $__env->startSection('content'); ?>
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Accueil</a></li>
        <li class="breadcrumb-item active">Demandes de réunion</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold">Demandes de réunion</h3>
        <p class="text-muted mb-0 small">Accueil / Demandes de réunion</p>
    </div>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\MeetingRequest::class)): ?>
    <a href="<?php echo e(route('meeting-requests.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Nouvelle demande
    </a>
    <?php endif; ?>
</div>

<?php echo $__env->make('partials.alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('meeting-requests.index')); ?>" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small">Recherche</label>
                <input type="text" name="q" class="form-control" value="<?php echo e($search); ?>" placeholder="Titre ou description">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Statut</label>
                <select name="status" class="form-select">
                    <option value="">Tous</option>
                    <option value="pending" <?php if($status === 'pending'): echo 'selected'; endif; ?>>En attente</option>
                    <option value="approved" <?php if($status === 'approved'): echo 'selected'; endif; ?>>Approuvées</option>
                    <option value="rejected" <?php if($status === 'rejected'): echo 'selected'; endif; ?>>Rejetées</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="bi bi-search me-1"></i> Filtrer
                </button>
            </div>
        </form>
    </div>
</div>


<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Titre</th>
                        <th>Type</th>
                        <th>Date demandée</th>
                        <th>Demandeur</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <a href="<?php echo e(route('meeting-requests.show', $request)); ?>" class="text-decoration-none fw-semibold">
                                    <?php echo e($request->title); ?>

                                </a>
                            </td>
                            <td><?php echo e($request->meetingType->name ?? '—'); ?></td>
                            <td><?php echo e($request->requested_start_at->format('d/m/Y H:i')); ?></td>
                            <td><?php echo e($request->requester->name); ?></td>
                            <td>
                                <?php
                                    $statusClass = match($request->status) {
                                        'pending' => 'bg-warning text-dark',
                                        'approved' => 'bg-success',
                                        'rejected' => 'bg-danger',
                                        default => 'bg-secondary'
                                    };
                                ?>
                                <span class="badge <?php echo e($statusClass); ?>"><?php echo e(ucfirst($request->status)); ?></span>
                            </td>
                            <td class="text-end">
                                <a href="<?php echo e(route('meeting-requests.show', $request)); ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                Aucune demande trouvée.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($requests->hasPages()): ?>
        <div class="modern-card-footer">
            <div class="small text-muted">
                Affichage de <?php echo e($requests->firstItem()); ?> à <?php echo e($requests->lastItem()); ?> 
                sur <?php echo e($requests->total()); ?> demande<?php echo e($requests->total() > 1 ? 's' : ''); ?>

            </div>
            <div class="pagination-modern">
                <?php echo e($requests->appends(request()->query())->links()); ?>

            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\meeting-requests\index.blade.php ENDPATH**/ ?>