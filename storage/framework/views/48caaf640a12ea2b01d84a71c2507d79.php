

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Types de documents</h4>
        <div class="small">
            <a href="<?php echo e(route('dashboard')); ?>" class="text-decoration-none text-muted">Accueil</a>
            <span class="text-muted">/</span>
            <span class="text-muted">Types de documents</span>
        </div>
        <p class="text-muted mb-0 mt-1">Gestion des types de documents du système SGRS-CEEAC.</p>
    </div>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\DocumentType::class)): ?>
    <a href="<?php echo e(route('document-types.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Nouveau type
    </a>
    <?php endif; ?>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('document-types.index')); ?>" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small">Recherche</label>
                <input type="text" name="q" class="form-control"
                       value="<?php echo e($search); ?>" placeholder="Nom, code ou description">
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
                        <th>Description</th>
                        <th>Validation requise</th>
                        <th>Documents</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $documentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $documentType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div class="fw-semibold"><?php echo e($documentType->name); ?></div>
                        </td>
                        <td>
                            <code class="text-primary"><?php echo e($documentType->code); ?></code>
                        </td>
                        <td>
                            <small class="text-muted"><?php echo e($documentType->description ? Str::limit($documentType->description, 50) : '-'); ?></small>
                        </td>
                        <td>
                            <?php if($documentType->requires_validation): ?>
                                <span class="badge bg-warning">Oui</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Non</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge bg-info"><?php echo e($documentType->documents_count); ?></span>
                        </td>
                        <td>
                            <?php if($documentType->is_active): ?>
                                <span class="badge bg-success">Actif</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactif</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="<?php echo e(route('document-types.show', $documentType)); ?>" class="btn btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $documentType)): ?>
                                <a href="<?php echo e(route('document-types.edit', $documentType)); ?>" class="btn btn-outline-secondary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $documentType)): ?>
                                <form method="POST" action="<?php echo e(route('document-types.destroy', $documentType)); ?>" 
                                      class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce type de document ?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            Aucun type de document trouvé.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($documentTypes->hasPages()): ?>
        <div class="modern-card-footer">
            <div class="small text-muted">
                Affichage de <?php echo e($documentTypes->firstItem()); ?> à <?php echo e($documentTypes->lastItem()); ?> 
                sur <?php echo e($documentTypes->total()); ?> type<?php echo e($documentTypes->total() > 1 ? 's' : ''); ?> de document
            </div>
            <div class="pagination-modern">
                <?php echo e($documentTypes->appends(request()->query())->links()); ?>

            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\document-types\index.blade.php ENDPATH**/ ?>