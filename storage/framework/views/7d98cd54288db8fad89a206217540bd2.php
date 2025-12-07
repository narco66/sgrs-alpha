

<?php $__env->startSection('title', $organizationCommittee->name); ?>

<?php $__env->startSection('content'); ?>
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Accueil</a></li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('organization-committees.index')); ?>">Comités d'organisation</a></li>
        <li class="breadcrumb-item active"><?php echo e(Str::limit($organizationCommittee->name, 30)); ?></li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold"><?php echo e($organizationCommittee->name); ?></h3>
        <p class="text-muted mb-0 small">Accueil / Comités d'organisation / Détails</p>
    </div>
    <div>
        <a href="<?php echo e(route('organization-committees.pdf', $organizationCommittee)); ?>" class="btn btn-outline-primary">
            <i class="bi bi-file-earmark-pdf me-1"></i> PDF
        </a>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $organizationCommittee)): ?>
            <a href="<?php echo e(route('organization-committees.edit', $organizationCommittee)); ?>" class="btn btn-outline-primary">
                <i class="bi bi-pencil me-1"></i> Modifier
            </a>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $organizationCommittee)): ?>
            <form action="<?php echo e(route('organization-committees.destroy', $organizationCommittee)); ?>" method="POST" class="d-inline">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Êtes-vous sûr ?')">
                    <i class="bi bi-trash me-1"></i> Supprimer
                </button>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php echo $__env->make('partials.alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Informations du comité</h5>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-3">Nom</dt>
                    <dd class="col-sm-9"><?php echo e($organizationCommittee->name); ?></dd>
                    
                    <dt class="col-sm-3">Description</dt>
                    <dd class="col-sm-9"><?php echo e($organizationCommittee->description ?? 'Aucune description'); ?></dd>
                    
                    <dt class="col-sm-3">Réunion associée</dt>
                    <dd class="col-sm-9">
                        <?php if($organizationCommittee->meeting): ?>
                            <a href="<?php echo e(route('meetings.show', $organizationCommittee->meeting)); ?>">
                                <?php echo e($organizationCommittee->meeting->title); ?>

                            </a>
                        <?php else: ?>
                            <span class="text-muted">Aucune</span>
                        <?php endif; ?>
                    </dd>
                    
                    <dt class="col-sm-3">Créé par</dt>
                    <dd class="col-sm-9"><?php echo e($organizationCommittee->creator->name ?? 'N/A'); ?></dd>
                    
                    <dt class="col-sm-3">Date de création</dt>
                    <dd class="col-sm-9"><?php echo e($organizationCommittee->created_at->format('d/m/Y H:i')); ?></dd>
                    
                    <dt class="col-sm-3">Statut</dt>
                    <dd class="col-sm-9">
                        <?php if($organizationCommittee->is_active): ?>
                            <span class="badge bg-success">Actif</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Inactif</span>
                        <?php endif; ?>
                    </dd>
                </dl>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Membres du comité (<?php echo e($organizationCommittee->members->count()); ?>)</h5>
            </div>
            <div class="card-body">
                <?php if($organizationCommittee->members->isNotEmpty()): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Utilisateur</th>
                                    <th>Rôle</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $organizationCommittee->members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($member->user->name); ?></td>
                                        <td><span class="badge bg-primary"><?php echo e($member->role); ?></span></td>
                                        <td><?php echo e($member->notes ?? '—'); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-0">Aucun membre dans ce comité.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\organization-committees\show.blade.php ENDPATH**/ ?>