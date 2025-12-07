<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Types de réunions</h4>
        <div class="small">
            <a href="<?php echo e(route('dashboard')); ?>" class="text-decoration-none text-muted">Accueil</a>
            <span class="text-muted">/</span>
            <span class="text-muted">Types de réunions</span>
        </div>
        <p class="text-muted mb-0 mt-1">Paramétrage des catégories de réunions statutaires (CCE, CDM, etc.).</p>
    </div>
    <a href="<?php echo e(route('meeting-types.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Nouveau type
    </a>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('meeting-types.index')); ?>" class="row g-2 align-items-end">
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
                        <th>Approbations</th>
                        <th>Ordre</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <a href="<?php echo e(route('meeting-types.show', $type)); ?>" class="fw-semibold text-decoration-none">
                                <?php echo e($type->name); ?>

                            </a>
                            <?php if($type->description): ?>
                                <div class="small text-muted">
                                    <?php echo e(\Illuminate\Support\Str::limit($type->description, 80)); ?>

                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                <?php echo e($type->code); ?>

                            </span>
                        </td>
                        <td class="small">
                            <?php if($type->requires_president_approval): ?>
                                <span class="badge bg-outline border border-danger text-danger mb-1">
                                    Présidence
                                </span>
                            <?php endif; ?>
                            <?php if($type->requires_sg_approval): ?>
                                <span class="badge bg-outline border border-primary text-primary mb-1">
                                    Secrétariat Général
                                </span>
                            <?php endif; ?>
                            <?php if(!$type->requires_president_approval && !$type->requires_sg_approval): ?>
                                <span class="text-muted">Aucune</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge bg-secondary-subtle text-secondary-emphasis">
                                <?php echo e($type->sort_order); ?>

                            </span>
                        </td>
                        <td>
                            <?php if($type->is_active): ?>
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
                            <a href="<?php echo e(route('meeting-types.edit', $type)); ?>"
                               class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="<?php echo e(route('meeting-types.destroy', $type)); ?>"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Supprimer ce type de réunion ?');">
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
                            Aucun type de réunion défini pour le moment.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($types->hasPages()): ?>
            <div class="modern-card-footer">
                <div class="small text-muted">
                    Affichage de <?php echo e($types->firstItem()); ?> à <?php echo e($types->lastItem()); ?> 
                    sur <?php echo e($types->total()); ?> type<?php echo e($types->total() > 1 ? 's' : ''); ?> de réunion
                </div>
                <div class="pagination-modern">
                    <?php echo e($types->appends(request()->query())->links()); ?>

                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\meeting_types\index.blade.php ENDPATH**/ ?>