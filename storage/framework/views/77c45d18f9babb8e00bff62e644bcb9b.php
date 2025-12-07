<?php echo csrf_field(); ?>

<div class="mb-3">
    <label class="form-label">Nom du comité</label>
    <input type="text" name="name" class="form-control"
           value="<?php echo e(old('name', $committee->name ?? '')); ?>" required>
</div>

<div class="mb-3">
    <label class="form-label">Code</label>
    <input type="text" name="code" class="form-control"
           value="<?php echo e(old('code', $committee->code ?? '')); ?>" required>
    <div class="form-text">Exemple : CE, CS, CT...</div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Type de réunion associé</label>
        <select name="meeting_type_id" class="form-select">
            <option value="">(Aucun)</option>
            <?php $__currentLoopData = $meetingTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($type->id); ?>"
                    <?php if(old('meeting_type_id', $committee->meeting_type_id ?? null) == $type->id): echo 'selected'; endif; ?>>
                    <?php echo e($type->name); ?> (<?php echo e($type->code); ?>)
                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Nature</label>
        <select name="is_permanent" class="form-select">
            <option value="1" <?php if(old('is_permanent', $committee->is_permanent ?? true)): echo 'selected'; endif; ?>>Permanent</option>
            <option value="0" <?php if(old('is_permanent', $committee->is_permanent ?? true) == false): echo 'selected'; endif; ?>>Ad hoc</option>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Statut</label>
        <select name="is_active" class="form-select">
            <option value="1" <?php if(old('is_active', $committee->is_active ?? true)): echo 'selected'; endif; ?>>Actif</option>
            <option value="0" <?php if(old('is_active', $committee->is_active ?? true) == false): echo 'selected'; endif; ?>>Inactif</option>
        </select>
    </div>
</div>

<div class="mt-3 mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" rows="3" class="form-control"><?php echo e(old('description', $committee->description ?? '')); ?></textarea>
</div>

<div class="mb-3">
    <label class="form-label">Ordre d'affichage</label>
    <input type="number" name="sort_order" class="form-control"
           value="<?php echo e(old('sort_order', $committee->sort_order ?? 0)); ?>">
</div>
<?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\committees\_form.blade.php ENDPATH**/ ?>