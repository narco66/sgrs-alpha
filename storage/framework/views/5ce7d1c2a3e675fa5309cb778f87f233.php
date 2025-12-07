

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Statistiques sur les réunions</h4>
        <p class="text-muted mb-0">
            Analyse des réunions par type, statut et période.
        </p>
    </div>
    <div class="btn-group">
        <a href="<?php echo e(route('reports.export', ['meetings', 'pdf'])); ?>?start_date=<?php echo e($startDate->format('Y-m-d')); ?>&end_date=<?php echo e($endDate->format('Y-m-d')); ?>" 
           class="btn btn-outline-danger" target="_blank">
            <i class="bi bi-file-pdf"></i> Export PDF
        </a>
        <a href="<?php echo e(route('reports.export', ['meetings', 'excel'])); ?>?start_date=<?php echo e($startDate->format('Y-m-d')); ?>&end_date=<?php echo e($endDate->format('Y-m-d')); ?>" 
           class="btn btn-outline-success">
            <i class="bi bi-file-excel"></i> Export Excel
        </a>
        <a href="<?php echo e(route('reports.index')); ?>" class="btn btn-outline-secondary">
            Retour aux rapports
        </a>
    </div>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('reports.meetings')); ?>" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small">Date de début</label>
                <input type="date" name="start_date" class="form-control" 
                       value="<?php echo e($startDate->format('Y-m-d')); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label small">Date de fin</label>
                <input type="date" name="end_date" class="form-control" 
                       value="<?php echo e($endDate->format('Y-m-d')); ?>">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search me-1"></i> Filtrer
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h6 class="mb-0">Réunions par type</h6>
            </div>
            <div class="card-body">
                <?php if($byType->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th class="text-end">Nombre</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $byType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($item->type ?? 'Non spécifié'); ?></td>
                                        <td class="text-end"><strong><?php echo e($item->total); ?></strong></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center mb-0">Aucune donnée disponible</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h6 class="mb-0">Réunions par statut</h6>
            </div>
            <div class="card-body">
                <?php if($byStatus->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Statut</th>
                                    <th class="text-end">Nombre</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $byStatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <span class="badge bg-secondary"><?php echo e($item->status); ?></span>
                                        </td>
                                        <td class="text-end"><strong><?php echo e($item->total); ?></strong></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center mb-0">Aucune donnée disponible</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if($avgConvocationDelay && $avgConvocationDelay->avg_delay): ?>
<div class="card shadow-sm border-0 mt-4">
    <div class="card-body">
        <h6 class="mb-3">Indicateurs de performance</h6>
        <div class="row">
            <div class="col-md-6">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-clock-history fs-2 text-primary"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="mb-0"><?php echo e(number_format($avgConvocationDelay->avg_delay, 1)); ?> jours</h5>
                        <small class="text-muted">Délai moyen de convocation</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\reports\meetings.blade.php ENDPATH**/ ?>