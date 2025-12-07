

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Délégations</h4>
        <div class="small">
            <a href="<?php echo e(route('dashboard')); ?>" class="text-decoration-none text-muted">Accueil</a>
            <span class="text-muted">/</span>
            <a href="<?php echo e(route('delegations.index')); ?>" class="text-decoration-none text-muted">Délégations</a>
        </div>
        <p class="text-muted mb-0 mt-1">Gestion des délégations des États membres de la CEEAC.</p>
    </div>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Delegation::class)): ?>
    <a href="<?php echo e(route('delegations.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Nouvelle délégation
    </a>
    <?php endif; ?>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('delegations.index')); ?>" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small">Recherche</label>
                <input type="text" name="q" class="form-control"
                       value="<?php echo e($search); ?>" placeholder="Titre, code ou pays">
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
                        <th>Titre</th>
                        <th>Code</th>
                        <th>Pays</th>
                        <th>Utilisateurs</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $delegations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $delegation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <a href="<?php echo e(route('delegations.show', $delegation)); ?>"
                               class="fw-semibold text-decoration-none">
                                <?php echo e($delegation->title); ?>

                            </a>
                            <?php if($delegation->description): ?>
                                <div class="small text-muted">
                                    <?php echo e(\Illuminate\Support\Str::limit($delegation->description, 80)); ?>

                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($delegation->code): ?>
                                <span class="badge bg-light text-dark border">
                                    <?php echo e($delegation->code); ?>

                                </span>
                            <?php else: ?>
                                <span class="text-muted small">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($delegation->country): ?>
                                <?php echo e($delegation->country); ?>

                            <?php else: ?>
                                <span class="text-muted small">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge bg-info-subtle text-info-emphasis">
                                <?php echo e($delegation->users_count); ?> utilisateur(s)
                            </span>
                        </td>
                        <td>
                            <?php if($delegation->is_active): ?>
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
                            <a href="<?php echo e(route('delegations.show', $delegation)); ?>"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $delegation)): ?>
                            <a href="<?php echo e(route('delegations.edit', $delegation)); ?>"
                               class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $delegation)): ?>
                            <form action="<?php echo e(route('delegations.destroy', $delegation)); ?>"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Supprimer cette délégation ?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Aucune délégation définie pour le moment.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($delegations->hasPages()): ?>
            <div class="modern-card-footer">
                <div class="small text-muted">
                    Affichage de <?php echo e($delegations->firstItem()); ?> à <?php echo e($delegations->lastItem()); ?> 
                    sur <?php echo e($delegations->total()); ?> délégation<?php echo e($delegations->total() > 1 ? 's' : ''); ?>

                </div>
                <div class="pagination-modern">
                    <?php echo e($delegations->appends(request()->query())->links()); ?>

                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views/delegations/index.blade.php ENDPATH**/ ?>