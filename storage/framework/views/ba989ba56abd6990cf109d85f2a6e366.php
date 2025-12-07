<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['label', 'name', 'type' => 'text', 'required' => false, 'help' => null, 'placeholder' => null]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['label', 'name', 'type' => 'text', 'required' => false, 'help' => null, 'placeholder' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="mb-4">
    <label for="<?php echo e($name); ?>" class="form-label">
        <?php if($required): ?>
            <span class="text-danger">*</span>
        <?php endif; ?>
        <?php echo e($label); ?>

    </label>
    
    <?php if($type === 'textarea'): ?>
        <textarea 
            class="form-control <?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
            id="<?php echo e($name); ?>" 
            name="<?php echo e($name); ?>" 
            rows="4"
            placeholder="<?php echo e($placeholder); ?>"
            <?php echo e($required ? 'required' : ''); ?>

        ><?php echo e(old($name, $slot ?? '')); ?></textarea>
    <?php elseif($type === 'select'): ?>
        <select 
            class="form-select <?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
            id="<?php echo e($name); ?>" 
            name="<?php echo e($name); ?>"
            <?php echo e($required ? 'required' : ''); ?>

        >
            <?php echo e($slot); ?>

        </select>
    <?php elseif($type === 'checkbox' || $type === 'radio'): ?>
        <div class="form-check">
            <input 
                class="form-check-input <?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                type="<?php echo e($type); ?>" 
                id="<?php echo e($name); ?>" 
                name="<?php echo e($name); ?>"
                value="<?php echo e($value ?? '1'); ?>"
                <?php echo e(old($name) ? 'checked' : ''); ?>

                <?php echo e($required ? 'required' : ''); ?>

            >
            <label class="form-check-label" for="<?php echo e($name); ?>">
                <?php echo e($slot); ?>

            </label>
        </div>
    <?php else: ?>
        <input 
            type="<?php echo e($type); ?>" 
            class="form-control <?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
            id="<?php echo e($name); ?>" 
            name="<?php echo e($name); ?>" 
            value="<?php echo e(old($name, $value ?? '')); ?>"
            placeholder="<?php echo e($placeholder); ?>"
            <?php echo e($required ? 'required' : ''); ?>

            <?php echo e($attributes); ?>

        >
    <?php endif; ?>

    <?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-feedback">
            <i class="bi bi-exclamation-circle me-1"></i>
            <?php echo e($message); ?>

        </div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

    <?php if($help): ?>
        <div class="form-text">
            <i class="bi bi-info-circle me-1"></i>
            <?php echo e($help); ?>

        </div>
    <?php endif; ?>
</div>

<?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\components\form-group.blade.php ENDPATH**/ ?>