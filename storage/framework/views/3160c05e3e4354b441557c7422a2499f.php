<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Journal des actions</h4>
        <div class="small">
            <a href="<?php echo e(route('dashboard')); ?>" class="text-decoration-none text-muted">Accueil</a>
            <span class="text-muted">/</span>
            <span class="text-muted">Journal des actions</span>
        </div>
        <p class="text-muted mb-0 mt-1">Suivi des opérations effectuées sur les réunions et autres objets audités.</p>
    </div>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('audit-logs.index')); ?>" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small">Événement</label>
                <select name="event" class="form-select">
                    <option value="">Tous</option>
                    <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eventOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($eventOption); ?>" <?php if($filters['event'] === $eventOption): echo 'selected'; endif; ?>>
                            <?php echo e($eventOption); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small">Utilisateur (ID)</label>
                <input type="number" name="user_id" value="<?php echo e($filters['user_id']); ?>" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label small">Modèle (classe)</label>
                <input type="text" name="model" value="<?php echo e($filters['model']); ?>"
                       class="form-control"
                       placeholder="App\Models\Meeting">
            </div>
            <div class="col-md-2 text-end">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="bi bi-funnel me-1"></i> Filtrer
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 align-middle">
                <thead class="table-light">
                    <tr class="small text-muted">
                        <th>Date / heure</th>
                        <th>Événement</th>
                        <th>Objet</th>
                        <th>Utilisateur</th>
                        <th>Anciennes valeurs</th>
                        <th>Nouvelles valeurs</th>
                    </tr>
                </thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="small">
                        <td>
                            <?php echo e($log->created_at?->format('d/m/Y H:i:s')); ?>

                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                <?php echo e($log->event); ?>

                            </span>
                        </td>
                        <td>
                            <div class="fw-semibold">
                                <?php echo e(class_basename($log->auditable_type)); ?> #<?php echo e($log->auditable_id); ?>

                            </div>
                            <div class="text-muted">
                                <?php echo e($log->auditable_type); ?>

                            </div>
                        </td>
                        <td>
                            <?php if($log->user): ?>
                                <div class="fw-semibold">
                                    <?php echo e($log->user->name); ?>

                                </div>
                                <div class="text-muted">
                                    ID <?php echo e($log->user->id); ?>

                                </div>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Système</span>
                            <?php endif; ?>
                        </td>
                        <td style="max-width: 220px;">
                            <?php if($log->old_values): ?>
                                <pre class="mb-0 small text-muted"
                                     style="white-space: pre-wrap; word-break: break-word;">
<?php echo e(json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?></pre>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td style="max-width: 220px;">
                            <?php if($log->new_values): ?>
                                <pre class="mb-0 small text-muted"
                                     style="white-space: pre-wrap; word-break: break-word;">
<?php echo e(json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?></pre>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Aucun événement d’audit enregistré.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($logs->hasPages()): ?>
            <div class="modern-card-footer">
                <div class="small text-muted">
                    Affichage de <?php echo e($logs->firstItem()); ?> à <?php echo e($logs->lastItem()); ?> 
                    sur <?php echo e($logs->total()); ?> action<?php echo e($logs->total() > 1 ? 's' : ''); ?>

                </div>
                <div class="pagination-modern">
                    <?php echo e($logs->appends(request()->query())->links()); ?>

                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\audit_logs\index.blade.php ENDPATH**/ ?>