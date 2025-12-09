<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-center align-items-center" style="min-height: 60vh;">
    <div class="card shadow-lg border-0" style="max-width: 620px; width: 100%;">
        <div class="card-body text-center p-5">
            <div class="mb-3">
                <span class="badge bg-danger-subtle text-danger border border-danger">Erreur 403</span>
            </div>
            <h3 class="mb-3">Accès refusé</h3>
            <p class="text-muted mb-4">
                Vous n'avez pas les permissions nécessaires pour consulter cette page.
                Si vous pensez qu'il s'agit d'une erreur, contactez un administrateur ou essayez avec un autre compte.
            </p>
            <div class="d-flex justify-content-center gap-2">
                <a href="<?php echo e(url()->previous()); ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left-circle me-1"></i> Retour
                </a>
                <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-primary">
                    <i class="bi bi-house-door me-1"></i> Accueil
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views/errors/403.blade.php ENDPATH**/ ?>