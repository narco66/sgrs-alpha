<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-center">
    <div class="card shadow-lg border-0" style="max-width: 800px; width: 100%;">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Ajouter un document</h5>
            <a href="<?php echo e(route('documents.index')); ?>" class="btn btn-sm btn-outline-secondary">
                Retour
            </a>
        </div>
        <div class="card-body">
            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('documents.store')); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>

                <div class="mb-3">
                    <label class="form-label">Titre du document</label>
                    <input type="text" name="title" class="form-control"
                           value="<?php echo e(old('title')); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Description <span class="text-muted small">(optionnelle)</span>
                    </label>
                    <textarea name="description"
                              class="form-control"
                              rows="3"><?php echo e(old('description')); ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Fichier</label>
                    <input type="file" name="file" class="form-control" required>
                    <div class="form-text">
                        Formats recommandés : PDF, Word, Excel, PowerPoint. Taille maximale : 25 Mo.
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Type de document</label>
                        <select name="document_type" class="form-select" required>
                            <option value="ordre_du_jour" <?php if(old('document_type') === 'ordre_du_jour'): echo 'selected'; endif; ?>>Ordre du jour</option>
                            <option value="rapport" <?php if(old('document_type') === 'rapport'): echo 'selected'; endif; ?>>Rapport</option>
                            <option value="pv" <?php if(old('document_type') === 'pv'): echo 'selected'; endif; ?>>Procès-verbal</option>
                            <option value="presentation" <?php if(old('document_type') === 'presentation'): echo 'selected'; endif; ?>>Présentation</option>
                            <option value="note" <?php if(old('document_type') === 'note'): echo 'selected'; endif; ?>>Note</option>
                            <option value="autre" <?php if(old('document_type') === 'autre' || !old('document_type')): echo 'selected'; endif; ?>>Autre</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Réunion associée</label>
                        <select name="meeting_id" class="form-select">
                            <option value="">(Aucune)</option>
                            <?php $__currentLoopData = $meetings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meeting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($meeting->id); ?>" <?php if(old('meeting_id') == $meeting->id): echo 'selected'; endif; ?>>
                                    <?php echo e($meeting->title); ?> – <?php echo e($meeting->start_at?->format('d/m/Y')); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Partage</label>
                    <select name="is_shared" class="form-select">
                        <option value="1" <?php if(old('is_shared', 1) == 1): echo 'selected'; endif; ?>>Document partagé avec les utilisateurs autorisés</option>
                        <option value="0" <?php if(old('is_shared') === '0'): echo 'selected'; endif; ?>>Document restreint</option>
                    </select>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="<?php echo e(route('documents.index')); ?>" class="btn btn-outline-secondary">
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Enregistrer le document
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\documents\create.blade.php ENDPATH**/ ?>