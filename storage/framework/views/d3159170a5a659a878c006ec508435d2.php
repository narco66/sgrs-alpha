<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['headers', 'emptyMessage' => 'Aucun élément trouvé.', 'emptyIcon' => 'bi-inbox']));

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

foreach (array_filter((['headers', 'emptyMessage' => 'Aucun élément trouvé.', 'emptyIcon' => 'bi-inbox']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="modern-table">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <?php $__currentLoopData = $headers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $header): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <th class="<?php echo e($header['sortable'] ?? false ? 'sortable' : ''); ?>" 
                            <?php if(isset($header['onclick'])): ?> onclick="<?php echo e($header['onclick']); ?>" <?php endif; ?>>
                            <?php echo e($header['label']); ?>

                            <?php if(isset($header['icon'])): ?>
                                <i class="bi <?php echo e($header['icon']); ?>"></i>
                            <?php endif; ?>
                        </th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tr>
            </thead>
            <tbody>
                <?php echo e($slot); ?>

                
                <?php if(empty($slot->toHtml()) || (isset($empty) && $empty)): ?>
                    <tr>
                        <td colspan="<?php echo e(count($headers)); ?>" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi <?php echo e($emptyIcon); ?> empty-state-icon"></i>
                                <div class="empty-state-title">Aucun résultat</div>
                                <div class="empty-state-text"><?php echo e($emptyMessage); ?></div>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\components\modern-table.blade.php ENDPATH**/ ?>