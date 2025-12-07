

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1"><?php echo e($document->title); ?></h4>
        <p class="text-muted mb-0">
            Détails du document
        </p>
    </div>
    <div class="btn-group">
        <a href="<?php echo e(route('documents.download', $document)); ?>" class="btn btn-outline-primary">
            <i class="bi bi-download me-1"></i> Télécharger
        </a>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $document)): ?>
        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#uploadVersionModal">
            <i class="bi bi-upload me-1"></i> Nouvelle version
        </button>
        <?php endif; ?>
        <a href="<?php echo e(route('documents.index')); ?>" class="btn btn-outline-secondary">
            Retour à la liste
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-white">
                <h6 class="mb-0">Informations du document</h6>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Titre</dt>
                    <dd class="col-sm-8"><?php echo e($document->title); ?></dd>

                    <dt class="col-sm-4">Description</dt>
                    <dd class="col-sm-8"><?php echo e($document->description ?? '-'); ?></dd>

                    <dt class="col-sm-4">Type de document</dt>
                    <dd class="col-sm-8">
                        <?php if($document->type): ?>
                            <span class="badge bg-primary"><?php echo e($document->type->name); ?></span>
                        <?php else: ?>
                            <span class="badge bg-secondary"><?php echo e($document->type_label); ?></span>
                        <?php endif; ?>
                    </dd>

                    <dt class="col-sm-4">Réunion associée</dt>
                    <dd class="col-sm-8">
                        <?php if($document->meeting): ?>
                            <a href="<?php echo e(route('meetings.show', $document->meeting)); ?>">
                                <?php echo e($document->meeting->title); ?>

                            </a>
                        <?php else: ?>
                            <span class="text-muted">Aucune</span>
                        <?php endif; ?>
                    </dd>

                    <dt class="col-sm-4">Auteur</dt>
                    <dd class="col-sm-8"><?php echo e($document->uploader->name); ?></dd>

                    <dt class="col-sm-4">Fichier</dt>
                    <dd class="col-sm-8">
                        <i class="<?php echo e($document->icon_class); ?> me-2"></i>
                        <?php echo e($document->original_name); ?>

                        <small class="text-muted">(<?php echo e(number_format($document->file_size / 1024, 2)); ?> KB)</small>
                    </dd>

                    <dt class="col-sm-4">Statut de validation</dt>
                    <dd class="col-sm-8">
                        <?php
                            $statusColors = [
                                'draft' => 'secondary',
                                'pending' => 'warning',
                                'approved' => 'success',
                                'rejected' => 'danger',
                                'archived' => 'info',
                            ];
                            $color = $statusColors[$document->validation_status] ?? 'secondary';
                        ?>
                        <span class="badge bg-<?php echo e($color); ?>"><?php echo e(ucfirst($document->validation_status)); ?></span>
                    </dd>

                    <dt class="col-sm-4">Date de création</dt>
                    <dd class="col-sm-8"><?php echo e($document->created_at->format('d/m/Y H:i')); ?></dd>
                </dl>
            </div>
        </div>

        <?php if($document->validations->count() > 0): ?>
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-white">
                <h6 class="mb-0">Workflow de validation</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <?php $__currentLoopData = ['protocole', 'sg', 'president']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $validation = $document->validations->firstWhere('validation_level', $level);
                        ?>
                        <div class="d-flex align-items-start mb-3">
                            <div class="flex-shrink-0">
                                <?php if($validation && $validation->status === 'approved'): ?>
                                    <i class="bi bi-check-circle-fill text-success fs-4"></i>
                                <?php elseif($validation && $validation->status === 'rejected'): ?>
                                    <i class="bi bi-x-circle-fill text-danger fs-4"></i>
                                <?php else: ?>
                                    <i class="bi bi-circle text-muted fs-4"></i>
                                <?php endif; ?>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">
                                    <?php echo e(match($level) {
                                        'protocole' => 'Protocole',
                                        'sg' => 'Secrétariat Général',
                                        'president' => 'Président',
                                        default => $level
                                    }); ?>

                                </h6>
                                <?php if($validation): ?>
                                    <p class="mb-1">
                                        <span class="badge bg-<?php echo e($validation->status === 'approved' ? 'success' : ($validation->status === 'rejected' ? 'danger' : 'warning')); ?>">
                                            <?php echo e($validation->status_label); ?>

                                        </span>
                                        <?php if($validation->validated_by): ?>
                                            par <?php echo e($validation->validator->name); ?>

                                        <?php endif; ?>
                                    </p>
                                    <?php if($validation->comments): ?>
                                        <p class="text-muted small mb-0"><?php echo e($validation->comments); ?></p>
                                    <?php endif; ?>
                                    <?php if($validation->validated_at): ?>
                                        <small class="text-muted"><?php echo e($validation->validated_at->format('d/m/Y H:i')); ?></small>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <p class="text-muted mb-0">En attente</p>
                                <?php endif; ?>
                            </div>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $document)): ?>
                            <?php if($validation && $validation->status === 'pending'): ?>
                            <div class="flex-shrink-0">
                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#validateModal"
                                        data-level="<?php echo e($level); ?>"
                                        data-validation-id="<?php echo e($validation->id); ?>">
                                    Valider
                                </button>
                            </div>
                            <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if($document->versions->count() > 1): ?>
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h6 class="mb-0">Historique des versions (<?php echo e($document->versions->count()); ?>)</h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <?php $__currentLoopData = $document->versions->sortByDesc('version_number'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $version): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">Version <?php echo e($version->version_number); ?></h6>
                                    <p class="mb-1 text-muted small">
                                        <?php echo e($version->original_name); ?>

                                        <span class="ms-2">(<?php echo e(number_format($version->file_size / 1024, 2)); ?> KB)</span>
                                    </p>
                                    <?php if($version->change_summary): ?>
                                        <p class="mb-0 small"><?php echo e($version->change_summary); ?></p>
                                    <?php endif; ?>
                                    <small class="text-muted">
                                        Par <?php echo e($version->creator->name); ?> le <?php echo e($version->created_at->format('d/m/Y H:i')); ?>

                                    </small>
                                </div>
                                <?php if($version->version_number === $document->versions->max('version_number')): ?>
                                    <span class="badge bg-primary">Version actuelle</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal pour uploader une nouvelle version -->
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $document)): ?>
<div class="modal fade" id="uploadVersionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?php echo e(route('documents.upload-version', $document)); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Uploader une nouvelle version</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Fichier <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control" required accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Résumé des changements</label>
                        <textarea name="change_summary" rows="3" class="form-control" 
                                  placeholder="Décrivez les modifications apportées à cette version..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Uploader</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal pour valider un document -->
<div class="modal fade" id="validateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?php echo e(route('documents.validate', $document)); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Valider le document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="validation_level" id="validation_level">
                    <div class="mb-3">
                        <label class="form-label">Statut <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="approved">Approuvé</option>
                            <option value="rejected">Rejeté</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Commentaires</label>
                        <textarea name="comments" rows="3" class="form-control" 
                                  placeholder="Ajoutez des commentaires si nécessaire..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const validateModal = document.getElementById('validateModal');
    if (validateModal) {
        validateModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const level = button.getAttribute('data-level');
            document.getElementById('validation_level').value = level;
        });
    }
});
</script>
<?php endif; ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\documents\show.blade.php ENDPATH**/ ?>