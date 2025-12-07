<?php $__env->startSection('title', 'Modifier le comitǸ d\'organisation'); ?>

<?php $__env->startSection('content'); ?>
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Accueil</a></li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('organization-committees.index')); ?>">ComitǸs d'organisation</a></li>
        <li class="breadcrumb-item active">Modifier</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold">Modifier le comitǸ d'organisation</h3>
        <p class="text-muted mb-0 small">Accueil / ComitǸs d'organisation / Modifier</p>
    </div>
</div>

<?php echo $__env->make('partials.alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('organization-committees.update', $organizationCommittee)); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nom du comitǸ <span class="text-danger">*</span></label>
                    <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name" name="name" value="<?php echo e(old('name', $organizationCommittee->name)); ?>" required>
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
                    <label for="meeting_id" class="form-label">RǸunion associǸe</label>
                    <select class="form-select <?php $__errorArgs = ['meeting_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="meeting_id" name="meeting_id">
                        <option value="">Aucune</option>
                        <?php $__currentLoopData = $meetings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meeting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($meeting->id); ?>" <?php if(old('meeting_id', $organizationCommittee->meeting_id) == $meeting->id): echo 'selected'; endif; ?>><?php echo e($meeting->title); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
unset($__errorArgs, $__bag); ?>" id="description" name="description" rows="3"><?php echo e(old('description', $organizationCommittee->description)); ?></textarea>
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

                <div class="col-md-6 mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" <?php if(old('is_active', $organizationCommittee->is_active)): echo 'checked'; endif; ?>>
                        <label class="form-check-label" for="is_active">ComitǸ actif</label>
                    </div>
                </div>
            </div>

            <hr>

            <h5 class="mb-3">Membres du comitǸ</h5>
            <div id="members-container">
                <?php $__empty_1 = true; $__currentLoopData = $organizationCommittee->members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="member-row mb-3 p-3 border rounded">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="form-label">Utilisateur <span class="text-danger">*</span></label>
                                <select class="form-select member-user" name="members[<?php echo e($index); ?>][user_id]" required>
                                    <option value="">SǸlectionner un utilisateur</option>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($user->id); ?>" <?php if($member->user_id == $user->id): echo 'selected'; endif; ?>><?php echo e($user->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">R��le <span class="text-danger">*</span></label>
                                <input type="text" class="form-control member-role" name="members[<?php echo e($index); ?>][role]" value="<?php echo e($member->role); ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" class="btn btn-outline-danger w-100 remove-member">
                                    <i class="bi bi-trash"></i> Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="member-row mb-3 p-3 border rounded">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="form-label">Utilisateur <span class="text-danger">*</span></label>
                                <select class="form-select member-user" name="members[0][user_id]" required>
                                    <option value="">SǸlectionner un utilisateur</option>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">R��le <span class="text-danger">*</span></label>
                                <input type="text" class="form-control member-role" name="members[0][role]" value="member" required placeholder="ex: prǸsident, secrǸtaire, membre">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" class="btn btn-outline-danger w-100 remove-member" style="display: none;">
                                    <i class="bi bi-trash"></i> Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <template id="member-template">
                <div class="member-row mb-3 p-3 border rounded">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label">Utilisateur <span class="text-danger">*</span></label>
                            <select class="form-select member-user" name="members[__INDEX__][user_id]" required>
                                <option value="">SǸlectionner un utilisateur</option>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">R��le <span class="text-danger">*</span></label>
                            <input type="text" class="form-control member-role" name="members[__INDEX__][role]" value="member" required placeholder="ex: prǸsident, secrǸtaire, membre">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-outline-danger w-100 remove-member">
                                <i class="bi bi-trash"></i> Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            </template>

            <button type="button" class="btn btn-outline-primary mb-3" id="add-member">
                <i class="bi bi-plus-circle me-1"></i> Ajouter un membre
            </button>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="<?php echo e(route('organization-committees.show', $organizationCommittee)); ?>" class="btn btn-outline-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-1"></i> Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    const membersContainer = document.getElementById('members-container');
    const memberTemplate = document.getElementById('member-template');
    let memberIndex = membersContainer.querySelectorAll('.member-row').length;

    // Fonction pour réindexer les membres
    function reindexMembers() {
        const rows = membersContainer.querySelectorAll('.member-row');
        rows.forEach((row, index) => {
            row.querySelectorAll('select, input').forEach(input => {
                if (input.name && input.name.includes('members[')) {
                    // Remplacer l'ancien index par le nouveau
                    input.name = input.name.replace(/members\[\d+\]/, `members[${index}]`);
                }
            });
        });
        memberIndex = membersContainer.querySelectorAll('.member-row').length;
    }

    document.getElementById('add-member').addEventListener('click', function() {
        if (!memberTemplate?.content?.firstElementChild) return;

        const newRow = memberTemplate.content.firstElementChild.cloneNode(true);

        newRow.querySelectorAll('select, input').forEach(input => {
            if (input.name) {
                input.name = input.name.replace('__INDEX__', memberIndex);
            }
            if (input.tagName === 'SELECT') {
                input.selectedIndex = 0;
            }
            if (input.type === 'text') {
                input.value = 'member';
            }
        });

        membersContainer.appendChild(newRow);
        memberIndex++;
        reindexMembers();
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-member')) {
            const row = e.target.closest('.member-row');
            if (membersContainer.children.length > 1) {
                row.remove();
                reindexMembers();
            }
        }
    });

    // Réindexer avant la soumission du formulaire
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            reindexMembers();
            
            // Supprimer les membres vides (sans user_id sélectionné) pour éviter les erreurs de validation
            const rows = Array.from(membersContainer.querySelectorAll('.member-row'));
            rows.forEach(row => {
                const userSelect = row.querySelector('select[name*="[user_id]"]');
                if (userSelect && !userSelect.value) {
                    // Si le membre n'a pas de user_id et qu'il y a d'autres membres, le supprimer
                    if (membersContainer.children.length > 1) {
                        row.remove();
                    } else {
                        // Si c'est le dernier membre, désactiver les champs au lieu de le supprimer
                        row.querySelectorAll('select, input').forEach(input => {
                            input.removeAttribute('required');
                            input.disabled = true;
                        });
                    }
                }
            });
            
            // Réindexer à nouveau après suppression des membres vides
            reindexMembers();
            
            // S'assurer que le champ is_active est correctement géré
            const isActiveCheckbox = document.getElementById('is_active');
            if (isActiveCheckbox && !isActiveCheckbox.checked) {
                // Ajouter un champ caché pour indiquer que la checkbox n'est pas cochée
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'is_active';
                hiddenInput.value = '0';
                form.appendChild(hiddenInput);
            }
        });
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\organization-committees\edit.blade.php ENDPATH**/ ?>