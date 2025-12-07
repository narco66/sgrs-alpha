

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1"><?php echo e($documentType->name); ?></h4>
        <p class="text-muted mb-0">
            Détails du type de document
        </p>
    </div>
    <div class="btn-group">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $documentType)): ?>
        <a href="<?php echo e(route('document-types.edit', $documentType)); ?>" class="btn btn-outline-primary">
            <i class="bi bi-pencil me-1"></i> Modifier
        </a>
        <?php endif; ?>
        <a href="<?php echo e(route('document-types.index')); ?>" class="btn btn-outline-secondary">
            Retour à la liste
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-white">
                <h6 class="mb-0">Informations</h6>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Nom</dt>
                    <dd class="col-sm-8"><?php echo e($documentType->name); ?></dd>

                    <dt class="col-sm-4">Code</dt>
                    <dd class="col-sm-8"><code class="text-primary"><?php echo e($documentType->code); ?></code></dd>

                    <dt class="col-sm-4">Description</dt>
                    <dd class="col-sm-8"><?php echo e($documentType->description ?? '-'); ?></dd>

                    <dt class="col-sm-4">Validation requise</dt>
                    <dd class="col-sm-8">
                        <?php if($documentType->requires_validation): ?>
                            <span class="badge bg-warning">Oui</span>
                            <small class="text-muted ms-2">(Protocole → SG → Président)</small>
                        <?php else: ?>
                            <span class="badge bg-secondary">Non</span>
                        <?php endif; ?>
                    </dd>

                    <dt class="col-sm-4">Ordre d'affichage</dt>
                    <dd class="col-sm-8"><?php echo e($documentType->sort_order); ?></dd>

                    <dt class="col-sm-4">Statut</dt>
                    <dd class="col-sm-8">
                        <?php if($documentType->is_active): ?>
                            <span class="badge bg-success">Actif</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Inactif</span>
                        <?php endif; ?>
                    </dd>

                    <dt class="col-sm-4">Date de création</dt>
                    <dd class="col-sm-8"><?php echo e($documentType->created_at->format('d/m/Y H:i')); ?></dd>
                </dl>
            </div>
        </div>

        <?php if($documentType->documents->count() > 0): ?>
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h6 class="mb-0">Documents de ce type (<?php echo e($documentType->documents->count()); ?>)</h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <?php $__currentLoopData = $documentType->documents->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('documents.show', $document)); ?>" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-1"><?php echo e($document->title); ?></h6>
                                    <small class="text-muted"><?php echo e($document->created_at->format('d/m/Y')); ?></small>
                                </div>
                                <span class="badge bg-<?php echo e($document->validation_status === 'approved' ? 'success' : 'warning'); ?>">
                                    <?php echo e($document->validation_status); ?>

                                </span>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php if($documentType->documents->count() > 10): ?>
                    <div class="mt-3 text-center">
                        <a href="<?php echo e(route('documents.index', ['type' => $documentType->id])); ?>" class="btn btn-sm btn-outline-primary">
                            Voir tous les documents
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\document-types\show.blade.php ENDPATH**/ ?>