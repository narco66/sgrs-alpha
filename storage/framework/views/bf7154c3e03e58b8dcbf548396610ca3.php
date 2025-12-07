

<?php
    // Mapping simple des clés de session vers les types d'alertes modernes
    $flashTypes = [
        'success' => 'success',
        'error'   => 'danger',
        'warning' => 'warning',
        'info'    => 'info',
    ];
?>

<div class="mb-3">
    
    <?php $__currentLoopData = $flashTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if(session($key)): ?>
            <?php if (isset($component)) { $__componentOriginal682e217f64e93fddc2bc39228ca2c21e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal682e217f64e93fddc2bc39228ca2c21e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modern-alert','data' => ['type' => ''.e($type).'','dismissible' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modern-alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => ''.e($type).'','dismissible' => true]); ?>
                <?php echo session($key); ?>

             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal682e217f64e93fddc2bc39228ca2c21e)): ?>
<?php $attributes = $__attributesOriginal682e217f64e93fddc2bc39228ca2c21e; ?>
<?php unset($__attributesOriginal682e217f64e93fddc2bc39228ca2c21e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal682e217f64e93fddc2bc39228ca2c21e)): ?>
<?php $component = $__componentOriginal682e217f64e93fddc2bc39228ca2c21e; ?>
<?php unset($__componentOriginal682e217f64e93fddc2bc39228ca2c21e); ?>
<?php endif; ?>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    
    <?php if(session('status')): ?>
        <?php if (isset($component)) { $__componentOriginal682e217f64e93fddc2bc39228ca2c21e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal682e217f64e93fddc2bc39228ca2c21e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modern-alert','data' => ['type' => 'success','dismissible' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modern-alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'success','dismissible' => true]); ?>
            <?php echo session('status'); ?>

         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal682e217f64e93fddc2bc39228ca2c21e)): ?>
<?php $attributes = $__attributesOriginal682e217f64e93fddc2bc39228ca2c21e; ?>
<?php unset($__attributesOriginal682e217f64e93fddc2bc39228ca2c21e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal682e217f64e93fddc2bc39228ca2c21e)): ?>
<?php $component = $__componentOriginal682e217f64e93fddc2bc39228ca2c21e; ?>
<?php unset($__componentOriginal682e217f64e93fddc2bc39228ca2c21e); ?>
<?php endif; ?>
    <?php endif; ?>

    
    <?php if($errors->any()): ?>
        <?php if (isset($component)) { $__componentOriginal682e217f64e93fddc2bc39228ca2c21e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal682e217f64e93fddc2bc39228ca2c21e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modern-alert','data' => ['type' => 'danger','dismissible' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modern-alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'danger','dismissible' => true]); ?>
            <div class="fw-semibold mb-2">
                Une erreur est survenue. Merci de vérifier les informations saisies :
            </div>
            <ul class="mb-0 ps-3">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal682e217f64e93fddc2bc39228ca2c21e)): ?>
<?php $attributes = $__attributesOriginal682e217f64e93fddc2bc39228ca2c21e; ?>
<?php unset($__attributesOriginal682e217f64e93fddc2bc39228ca2c21e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal682e217f64e93fddc2bc39228ca2c21e)): ?>
<?php $component = $__componentOriginal682e217f64e93fddc2bc39228ca2c21e; ?>
<?php unset($__componentOriginal682e217f64e93fddc2bc39228ca2c21e); ?>
<?php endif; ?>
    <?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\sgrs-alpha\resources\views/partials/alerts.blade.php ENDPATH**/ ?>