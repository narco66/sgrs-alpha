

<?php $__env->startSection('title', 'Comités d\'organisation'); ?>

<?php $__env->startSection('content'); ?>
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Accueil</a></li>
        <li class="breadcrumb-item active">Comités d'organisation</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold">Comités d'organisation</h3>
        <p class="text-muted mb-0 small">Accueil / Comités d'organisation</p>
    </div>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\OrganizationCommittee::class)): ?>
    <a href="<?php echo e(route('organization-committees.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Nouveau comité
    </a>
    <?php endif; ?>
</div>

<?php echo $__env->make('partials.alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('organization-committees.index')); ?>" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small">Recherche</label>
                <input type="text" name="q" class="form-control" value="<?php echo e($search); ?>" placeholder="Nom ou description">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="bi bi-search me-1"></i> Rechercher
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
                        <th>Nom</th>
                        <th>Réunion associée</th>
                        <th>Membres</th>
                        <th>Créé par</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $committees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $committee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <a href="<?php echo e(route('organization-committees.show', $committee)); ?>" class="text-decoration-none fw-semibold">
                                    <?php echo e($committee->name); ?>

                                </a>
                                <?php if($committee->description): ?>
                                    <br><small class="text-muted"><?php echo e(Str::limit($committee->description, 50)); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($committee->meeting): ?>
                                    <a href="<?php echo e(route('meetings.show', $committee->meeting)); ?>" class="text-decoration-none">
                                        <?php echo e($committee->meeting->title); ?>

                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">Aucune</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    <i class="bi bi-people me-1"></i><?php echo e($committee->members->count()); ?> membre<?php echo e($committee->members->count() > 1 ? 's' : ''); ?>

                                </span>
                            </td>
                            <td><?php echo e($committee->creator->name ?? 'N/A'); ?></td>
                            <td>
                                <?php if($committee->is_active): ?>
                                    <span class="badge bg-success">Actif</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactif</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="<?php echo e(route('organization-committees.show', $committee)); ?>" class="btn btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $committee)): ?>
                                        <a href="<?php echo e(route('organization-committees.edit', $committee)); ?>" class="btn btn-outline-secondary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $committee)): ?>
                                        <form action="<?php echo e(route('organization-committees.destroy', $committee)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Êtes-vous sûr ?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                Aucun comité d'organisation trouvé.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($committees->hasPages()): ?>
        <div class="modern-card-footer">
            <div class="small text-muted">
                Affichage de <?php echo e($committees->firstItem()); ?> à <?php echo e($committees->lastItem()); ?> 
                sur <?php echo e($committees->total()); ?> comité<?php echo e($committees->total() > 1 ? 's' : ''); ?>

            </div>
            <div class="pagination-modern">
                <?php echo e($committees->appends(request()->query())->links()); ?>

            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\organization-committees\index.blade.php ENDPATH**/ ?>