<?php echo csrf_field(); ?>

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Titre de la réunion</label>
        <input type="text" name="title" class="form-control"
               value="<?php echo e(old('title', $meeting->title ?? '')); ?>" required>
    </div>

    <div class="col-md-3">
        <label class="form-label">Type de réunion</label>
        <select name="meeting_type_id" class="form-select">
            <option value="">(Non défini)</option>
            <?php $__currentLoopData = $meetingTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($type->id); ?>"
                    <?php if(old('meeting_type_id', $meeting->meeting_type_id ?? null) == $type->id): echo 'selected'; endif; ?>>
                    <?php echo e($type->name); ?> (<?php echo e($type->code); ?>)
                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    <div class="col-md-3">
        <label class="form-label">Comité</label>
        <select name="committee_id" class="form-select">
            <option value="">(Aucun)</option>
            <?php $__currentLoopData = $committees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $committee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($committee->id); ?>"
                    <?php if(old('committee_id', $meeting->committee_id ?? null) == $committee->id): echo 'selected'; endif; ?>>
                    <?php echo e($committee->name); ?> (<?php echo e($committee->code); ?>)
                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
</div>



<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3"><?php echo e(old('description', $meeting->description ?? '')); ?></textarea>
    </div>
</div>
<?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\meetings\_form.blade.php ENDPATH**/ ?>