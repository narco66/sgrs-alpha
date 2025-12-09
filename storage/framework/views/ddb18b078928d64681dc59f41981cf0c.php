<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Comités</h4>
        <div class="small">
            <a href="<?php echo e(route('dashboard')); ?>" class="text-decoration-none text-muted">Accueil</a>
            <span class="text-muted">/</span>
            <span class="text-muted">Comités</span>
        </div>
        <p class="text-muted mb-0 mt-1">Paramétrage des comités et groupes de travail liés aux réunions.</p>
    </div>
    <a href="<?php echo e(route('committees.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Nouveau comité
    </a>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('committees.index')); ?>" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small">Recherche</label>
                <input type="text" name="q" class="form-control"
                       value="<?php echo e($search); ?>" placeholder="Nom ou code">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="bi bi-search me-1"></i> Rechercher
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small text-muted">
                    <tr>
                        <th>Nom</th>
                        <th>Code</th>
                        <th>Type de réunion associé</th>
                        <th>Nature</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $committees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $committee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <a href="<?php echo e(route('committees.show', $committee)); ?>"
                               class="fw-semibold text-decoration-none">
                                <?php echo e($committee->name); ?>

                            </a>
                            <?php if($committee->description): ?>
                                <div class="small text-muted">
                                    <?php echo e(\Illuminate\Support\Str::limit($committee->description, 80)); ?>

                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                <?php echo e($committee->code); ?>

                            </span>
                        </td>
                        <td>
                            <?php if($committee->meetingType): ?>
                                <span class="badge bg-primary-subtle text-primary-emphasis">
                                    <?php echo e($committee->meetingType->name); ?>

                                </span>
                            <?php else: ?>
                                <span class="text-muted small">Non défini</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($committee->is_permanent): ?>
                                <span class="badge bg-info-subtle text-info-emphasis">
                                    Permanent
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary-subtle text-secondary-emphasis">
                                    Ad hoc
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($committee->is_active): ?>
                                <span class="badge bg-success-subtle text-success-emphasis">
                                    Actif
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary-subtle text-secondary-emphasis">
                                    Inactif
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <a href="<?php echo e(route('committees.edit', $committee)); ?>"
                               class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="<?php echo e(route('committees.destroy', $committee)); ?>"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Supprimer ce comité ?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Aucun comité défini pour le moment.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
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
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views/committees/index.blade.php ENDPATH**/ ?>