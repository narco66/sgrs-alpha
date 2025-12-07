

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Indicateurs de performance</h4>
        <p class="text-muted mb-0">
            Délais moyens de convocation, validation et archivage. Taux de complétion.
        </p>
    </div>
    <a href="<?php echo e(route('reports.index')); ?>" class="btn btn-outline-secondary">
        Retour aux rapports
    </a>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('reports.performance')); ?>" class="row g-2 align-items-end">
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
    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center">
            <div class="card-body">
                <div class="mb-2">
                    <i class="bi bi-clock-history fs-1 text-primary"></i>
                </div>
                <h3 class="text-primary mb-2">
                    <?php echo e($avgConvocationDelay && $avgConvocationDelay->avg_delay ? number_format($avgConvocationDelay->avg_delay, 1) : '0'); ?> jours
                </h3>
                <p class="text-muted mb-0">Délai moyen de convocation</p>
                <small class="text-muted">Temps entre création et date de réunion</small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center">
            <div class="card-body">
                <div class="mb-2">
                    <i class="bi bi-check-circle fs-1 text-success"></i>
                </div>
                <h3 class="text-success mb-2">
                    <?php echo e($avgValidationDelay && $avgValidationDelay->avg_delay ? number_format($avgValidationDelay->avg_delay, 1) : '0'); ?> jours
                </h3>
                <p class="text-muted mb-0">Délai moyen de validation</p>
                <small class="text-muted">Temps de validation des documents</small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center">
            <div class="card-body">
                <div class="mb-2">
                    <i class="bi bi-archive fs-1 text-info"></i>
                </div>
                <h3 class="text-info mb-2">
                    <?php echo e($avgArchivingDelay && $avgArchivingDelay->avg_delay ? number_format($avgArchivingDelay->avg_delay, 1) : '0'); ?> jours
                </h3>
                <p class="text-muted mb-0">Délai moyen d'archivage</p>
                <small class="text-muted">Temps avant archivage des documents</small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center">
            <div class="card-body">
                <div class="mb-2">
                    <i class="bi bi-check2-all fs-1 text-warning"></i>
                </div>
                <h3 class="text-warning mb-2">
                    <?php echo e(number_format($completionRate, 1)); ?>%
                </h3>
                <p class="text-muted mb-0">Taux de complétion</p>
                <small class="text-muted">Réunions terminées / Total</small>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mt-4">
    <div class="card-header bg-white">
        <h6 class="mb-0">Analyse des performances</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6 class="mb-3">Objectifs de performance</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>Délai de convocation :</strong> Objectif &lt; 7 jours
                        <?php if($avgConvocationDelay && $avgConvocationDelay->avg_delay): ?>
                            <?php if($avgConvocationDelay->avg_delay <= 7): ?>
                                <span class="badge bg-success ms-2">Objectif atteint</span>
                            <?php else: ?>
                                <span class="badge bg-warning ms-2">À améliorer</span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>Délai de validation :</strong> Objectif &lt; 5 jours
                        <?php if($avgValidationDelay && $avgValidationDelay->avg_delay): ?>
                            <?php if($avgValidationDelay->avg_delay <= 5): ?>
                                <span class="badge bg-success ms-2">Objectif atteint</span>
                            <?php else: ?>
                                <span class="badge bg-warning ms-2">À améliorer</span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>Taux de complétion :</strong> Objectif &gt; 80%
                        <?php if($completionRate >= 80): ?>
                            <span class="badge bg-success ms-2">Objectif atteint</span>
                        <?php else: ?>
                            <span class="badge bg-warning ms-2">À améliorer</span>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6 class="mb-3">Recommandations</h6>
                <div class="alert alert-info mb-0">
                    <ul class="mb-0 small">
                        <?php if($avgConvocationDelay && $avgConvocationDelay->avg_delay > 7): ?>
                            <li>Réduire les délais de convocation en planifiant plus tôt</li>
                        <?php endif; ?>
                        <?php if($avgValidationDelay && $avgValidationDelay->avg_delay > 5): ?>
                            <li>Accélérer le processus de validation des documents</li>
                        <?php endif; ?>
                        <?php if($completionRate < 80): ?>
                            <li>Améliorer le suivi des réunions pour augmenter le taux de complétion</li>
                        <?php endif; ?>
                        <?php if((!$avgConvocationDelay || $avgConvocationDelay->avg_delay <= 7) && 
                            (!$avgValidationDelay || $avgValidationDelay->avg_delay <= 5) && 
                            $completionRate >= 80): ?>
                            <li>Tous les objectifs de performance sont atteints. Excellent travail !</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\reports\performance.blade.php ENDPATH**/ ?>