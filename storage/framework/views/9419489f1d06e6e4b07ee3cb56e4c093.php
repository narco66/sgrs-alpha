<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['action', 'method' => 'POST', 'title' => null, 'sections' => []]));

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

foreach (array_filter((['action', 'method' => 'POST', 'title' => null, 'sections' => []]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="modern-form">
    <?php if($title): ?>
        <div class="mb-4">
            <h3 class="page-title"><?php echo e($title); ?></h3>
        </div>
    <?php endif; ?>

    <form action="<?php echo e($action); ?>" method="<?php echo e($method === 'GET' ? 'GET' : 'POST'); ?>" <?php echo e($attributes); ?>>
        <?php if($method !== 'GET'): ?>
            <?php echo csrf_field(); ?>
            <?php if($method !== 'POST'): ?>
                <?php echo method_field($method); ?>
            <?php endif; ?>
        <?php endif; ?>

        <?php if(!empty($sections)): ?>
            <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="form-section">
                    <?php if(isset($section['title'])): ?>
                        <h5 class="form-section-title">
                            <?php if(isset($section['icon'])): ?>
                                <i class="bi <?php echo e($section['icon']); ?>"></i>
                            <?php endif; ?>
                            <?php echo e($section['title']); ?>

                        </h5>
                    <?php endif; ?>
                    <?php echo e($section['content'] ?? ''); ?>

                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <?php echo e($slot); ?>

        <?php endif; ?>

        <?php if(isset($footer)): ?>
            <div class="d-flex justify-content-end gap-2 mt-4 pt-4 border-top">
                <?php echo e($footer); ?>

            </div>
        <?php endif; ?>
    </form>
</div>
<?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\components\modern-form.blade.php ENDPATH**/ ?>