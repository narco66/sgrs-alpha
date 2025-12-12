

<?php $__env->startSection('title', 'Créer un comité d\'organisation'); ?>

<?php $__env->startSection('content'); ?>
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Accueil</a></li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('organization-committees.index')); ?>">Comités d'organisation</a></li>
        <li class="breadcrumb-item active">Créer</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold">Créer un comité d'organisation</h3>
        <p class="text-muted mb-0 small">Accueil / Comités d'organisation / Créer</p>
    </div>
</div>

<?php echo $__env->make('partials.alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('organization-committees.store')); ?>" id="committeeForm">
            <?php echo csrf_field(); ?>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nom du comité <span class="text-danger">*</span></label>
                    <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name" name="name" value="<?php echo e(old('name')); ?>" required>
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="meeting_id" class="form-label">Réunion associée</label>
                    <select class="form-select <?php $__errorArgs = ['meeting_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="meeting_id" name="meeting_id">
                        <option value="">Aucune</option>
                        <?php if($meeting): ?>
                            <option value="<?php echo e($meeting->id); ?>" selected><?php echo e($meeting->title); ?></option>
                        <?php endif; ?>
                    </select>
                    <?php $__errorArgs = ['meeting_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-12 mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="description" name="description" rows="3"><?php echo e(old('description')); ?></textarea>
                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <hr>

            <h5 class="mb-3">Membres du comité</h5>
            <div id="members-container">
                <div class="member-row mb-3 p-3 border rounded">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label">Utilisateur <span class="text-danger">*</span></label>
                            <select class="form-select member-user" name="members[0][user_id]" required>
                                <option value="">Sélectionner un utilisateur</option>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Rôle <span class="text-danger">*</span></label>
                            <input type="text" class="form-control member-role" name="members[0][role]" value="member" required placeholder="ex: président, secrétaire, membre">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-outline-danger w-100 remove-member" style="display: none;">
                                <i class="bi bi-trash"></i> Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-outline-primary mb-3" id="add-member">
                <i class="bi bi-plus-circle me-1"></i> Ajouter un membre
            </button>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="<?php echo e(route('organization-committees.index')); ?>" class="btn btn-outline-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-1"></i> Créer le comité
                </button>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    let memberIndex = 1;
    
    document.getElementById('add-member').addEventListener('click', function() {
        const container = document.getElementById('members-container');
        const newRow = container.firstElementChild.cloneNode(true);
        
        // Mettre à jour les noms des champs
        newRow.querySelectorAll('select, input').forEach(input => {
            if (input.name) {
                input.name = input.name.replace(/\[0\]/, `[${memberIndex}]`);
            }
            if (input.value && input.tagName === 'SELECT') {
                input.value = '';
            }
            if (input.value && input.tagName === 'INPUT' && input.type === 'text') {
                input.value = '';
            }
        });
        
        // Afficher le bouton supprimer
        newRow.querySelector('.remove-member').style.display = 'block';
        
        container.appendChild(newRow);
        memberIndex++;
    });
    
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-member')) {
            const row = e.target.closest('.member-row');
            if (document.getElementById('members-container').children.length > 1) {
                row.remove();
            }
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views/organization-committees/create.blade.php ENDPATH**/ ?>