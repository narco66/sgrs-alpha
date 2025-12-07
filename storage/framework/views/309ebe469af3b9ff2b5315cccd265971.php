

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Rapports et statistiques</h4>
        <div class="small">
            <a href="<?php echo e(route('dashboard')); ?>" class="text-decoration-none text-muted">Accueil</a>
            <span class="text-muted">/</span>
            <span class="text-muted">Rapports et statistiques</span>
        </div>
        <p class="text-muted mb-0 mt-1">Tableaux de bord et statistiques du système SGRS-CEEAC.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="bi bi-calendar-event fs-1 text-primary"></i>
                </div>
                <h5 class="mb-2">Statistiques sur les réunions</h5>
                <p class="text-muted small mb-3">
                    Nombre par type, statut, période. Délais moyens de convocation.
                </p>
                <a href="<?php echo e(route('reports.meetings')); ?>" class="btn btn-primary">
                    <i class="bi bi-arrow-right me-1"></i> Consulter
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="bi bi-people fs-1 text-success"></i>
                </div>
                <h5 class="mb-2">Statistiques sur les participants</h5>
                <p class="text-muted small mb-3">
                    Taux de participation global et par service. Présences et absences.
                </p>
                <a href="<?php echo e(route('reports.participants')); ?>" class="btn btn-success">
                    <i class="bi bi-arrow-right me-1"></i> Consulter
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="bi bi-file-earmark-text fs-1 text-info"></i>
                </div>
                <h5 class="mb-2">Statistiques sur les documents</h5>
                <p class="text-muted small mb-3">
                    Nombre de documents déposés, validés, rejetés, archivés. Délais de validation.
                </p>
                <a href="<?php echo e(route('reports.documents')); ?>" class="btn btn-info">
                    <i class="bi bi-arrow-right me-1"></i> Consulter
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="bi bi-speedometer2 fs-1 text-warning"></i>
                </div>
                <h5 class="mb-2">Indicateurs de performance</h5>
                <p class="text-muted small mb-3">
                    Délais moyens de convocation, validation et archivage. Taux de complétion.
                </p>
                <a href="<?php echo e(route('reports.performance')); ?>" class="btn btn-warning">
                    <i class="bi bi-arrow-right me-1"></i> Consulter
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mt-4">
    <div class="card-header bg-white">
        <h6 class="mb-0">
            <i class="bi bi-download me-2"></i>
            Exports disponibles
        </h6>
    </div>
    <div class="card-body">
        <p class="text-muted mb-3">
            Tous les rapports peuvent être exportés en format PDF (pour impression) ou Excel/CSV (pour analyse).
        </p>
        <div class="alert alert-success mb-0">
            <i class="bi bi-check-circle me-2"></i>
            <strong>Fonctionnalité disponible :</strong> Les exports PDF et Excel sont maintenant opérationnels. 
            Utilisez les boutons d'export disponibles sur chaque page de rapport.
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\reports\index.blade.php ENDPATH**/ ?>