
<?php if(isset($items) && count($items) > 0): ?>
<table style="width: 100%; margin: 10px 0;">
    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if(isset($item['value']) && $item['value'] !== null && $item['value'] !== ''): ?>
        <tr>
            <td style="width: 35%; background: #f9fafb; font-weight: bold; border: 1px solid #e5e7eb; padding: 6px 10px;">
                <?php echo e($item['label']); ?>

            </td>
            <td style="border: 1px solid #e5e7eb; padding: 6px 10px;">
                <?php if(isset($item['badge'])): ?>
                    <span class="badge badge-<?php echo e($item['badge']); ?>"><?php echo e($item['value']); ?></span>
                <?php else: ?>
                    <?php echo e($item['value']); ?>

                <?php endif; ?>
            </td>
        </tr>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</table>
<?php endif; ?>


<?php /**PATH C:\laragon\www\sgrs-alpha\resources\views/pdf/partials/info-table.blade.php ENDPATH**/ ?>