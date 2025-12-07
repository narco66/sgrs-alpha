<?php
    $memberId = $member['id'] ?? null;
    $isHead = ($member['role'] ?? '') === 'head';
?>

<div class="member-item border rounded p-3 mb-3 <?php echo e($isExisting ? 'bg-light' : ''); ?>">
    <?php if($isExisting): ?>
        <input type="hidden" name="members[<?php echo e($index); ?>][id]" value="<?php echo e($memberId); ?>">
    <?php endif; ?>
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0">
            <i class="bi bi-person"></i> 
            Membre <?php echo e($index + 1); ?>

            <?php if($isExisting): ?>
                <span class="badge bg-secondary">Existant</span>
            <?php endif; ?>
        </h6>
        <button type="button" class="btn btn-sm btn-outline-danger remove-member-btn">
            <i class="bi bi-trash"></i> Supprimer
        </button>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Prénom <span class="text-danger">*</span></label>
            <input type="text" 
                   name="members[<?php echo e($index); ?>][first_name]" 
                   class="form-control <?php $__errorArgs = ["members.{$index}.first_name"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   value="<?php echo e(old("members.{$index}.first_name", $member['first_name'] ?? '')); ?>"
                   required>
            <?php $__errorArgs = ["members.{$index}.first_name"];
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

        <div class="col-md-6">
            <label class="form-label">Nom <span class="text-danger">*</span></label>
            <input type="text" 
                   name="members[<?php echo e($index); ?>][last_name]" 
                   class="form-control <?php $__errorArgs = ["members.{$index}.last_name"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   value="<?php echo e(old("members.{$index}.last_name", $member['last_name'] ?? '')); ?>"
                   required>
            <?php $__errorArgs = ["members.{$index}.last_name"];
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

        <div class="col-md-6">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" 
                   name="members[<?php echo e($index); ?>][email]" 
                   class="form-control <?php $__errorArgs = ["members.{$index}.email"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   value="<?php echo e(old("members.{$index}.email", $member['email'] ?? '')); ?>"
                   required>
            <?php $__errorArgs = ["members.{$index}.email"];
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

        <div class="col-md-6">
            <label class="form-label">Téléphone</label>
            <input type="text" 
                   name="members[<?php echo e($index); ?>][phone]" 
                   class="form-control <?php $__errorArgs = ["members.{$index}.phone"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   value="<?php echo e(old("members.{$index}.phone", $member['phone'] ?? '')); ?>"
                   placeholder="+242 06 123 456 78">
            <?php $__errorArgs = ["members.{$index}.phone"];
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

        <div class="col-md-6">
            <label class="form-label">Fonction / Position</label>
            <input type="text" 
                   name="members[<?php echo e($index); ?>][position]" 
                   class="form-control <?php $__errorArgs = ["members.{$index}.position"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   value="<?php echo e(old("members.{$index}.position", $member['position'] ?? '')); ?>"
                   placeholder="Ex: Ministre, Ambassadeur, Conseiller">
            <?php $__errorArgs = ["members.{$index}.position"];
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

        <div class="col-md-6">
            <label class="form-label">Titre / Grade</label>
            <input type="text" 
                   name="members[<?php echo e($index); ?>][title]" 
                   class="form-control <?php $__errorArgs = ["members.{$index}.title"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   value="<?php echo e(old("members.{$index}.title", $member['title'] ?? '')); ?>"
                   placeholder="Ex: Son Excellence, Dr., Prof.">
            <?php $__errorArgs = ["members.{$index}.title"];
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

        <div class="col-md-6">
            <label class="form-label">Institution</label>
            <input type="text" 
                   name="members[<?php echo e($index); ?>][institution]" 
                   class="form-control <?php $__errorArgs = ["members.{$index}.institution"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   value="<?php echo e(old("members.{$index}.institution", $member['institution'] ?? '')); ?>"
                   placeholder="Ex: Ministère des Affaires Étrangères">
            <?php $__errorArgs = ["members.{$index}.institution"];
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

        <div class="col-md-6">
            <label class="form-label">Département / Service</label>
            <input type="text" 
                   name="members[<?php echo e($index); ?>][department]" 
                   class="form-control <?php $__errorArgs = ["members.{$index}.department"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   value="<?php echo e(old("members.{$index}.department", $member['department'] ?? '')); ?>">
            <?php $__errorArgs = ["members.{$index}.department"];
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

        <div class="col-md-6">
            <label class="form-label">Rôle dans la délégation <span class="text-danger">*</span></label>
            <select name="members[<?php echo e($index); ?>][role]" 
                    class="form-select <?php $__errorArgs = ["members.{$index}.role"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    required>
                <option value="">Sélectionner un rôle</option>
                <option value="head" <?php if(old("members.{$index}.role", $member['role'] ?? '') == 'head'): echo 'selected'; endif; ?>>
                    Chef de délégation
                </option>
                <option value="member" <?php if(old("members.{$index}.role", $member['role'] ?? 'member') == 'member'): echo 'selected'; endif; ?>>
                    Membre
                </option>
                <option value="expert" <?php if(old("members.{$index}.role", $member['role'] ?? '') == 'expert'): echo 'selected'; endif; ?>>
                    Expert
                </option>
                <option value="observer" <?php if(old("members.{$index}.role", $member['role'] ?? '') == 'observer'): echo 'selected'; endif; ?>>
                    Observateur
                </option>
                <option value="secretary" <?php if(old("members.{$index}.role", $member['role'] ?? '') == 'secretary'): echo 'selected'; endif; ?>>
                    Secrétaire
                </option>
            </select>
            <?php $__errorArgs = ["members.{$index}.role"];
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

        <div class="col-md-6">
            <label class="form-label">Statut</label>
            <select name="members[<?php echo e($index); ?>][status]" 
                    class="form-select <?php $__errorArgs = ["members.{$index}.status"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <option value="invited" <?php if(old("members.{$index}.status", $member['status'] ?? 'invited') == 'invited'): echo 'selected'; endif; ?>>
                    Invité
                </option>
                <option value="confirmed" <?php if(old("members.{$index}.status", $member['status'] ?? '') == 'confirmed'): echo 'selected'; endif; ?>>
                    Confirmé
                </option>
                <option value="present" <?php if(old("members.{$index}.status", $member['status'] ?? '') == 'present'): echo 'selected'; endif; ?>>
                    Présent
                </option>
                <option value="absent" <?php if(old("members.{$index}.status", $member['status'] ?? '') == 'absent'): echo 'selected'; endif; ?>>
                    Absent
                </option>
                <option value="excused" <?php if(old("members.{$index}.status", $member['status'] ?? '') == 'excused'): echo 'selected'; endif; ?>>
                    Excusé
                </option>
            </select>
            <?php $__errorArgs = ["members.{$index}.status"];
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

        <div class="col-12">
            <label class="form-label">Notes</label>
            <textarea name="members[<?php echo e($index); ?>][notes]" 
                      rows="2" 
                      class="form-control <?php $__errorArgs = ["members.{$index}.notes"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                      placeholder="Notes additionnelles sur ce membre..."><?php echo e(old("members.{$index}.notes", $member['notes'] ?? '')); ?></textarea>
            <?php $__errorArgs = ["members.{$index}.notes"];
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
</div>




<?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\delegations\_member_form.blade.php ENDPATH**/ ?>