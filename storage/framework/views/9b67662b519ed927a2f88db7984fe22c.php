<?php $__env->startSection('title', 'Notifications'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Mes Notifications</h5>
                    <?php if($notifications->count() > 0): ?>
                        <form action="<?php echo e(route('notifications.markAllAsRead')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                Tout marquer comme lu
                            </button>
                        </form>
                    <?php endif; ?>
                </div>

                <div class="card-body p-0">
                    <?php if($notifications->count() > 0): ?>
                        <div class="list-group list-group-flush">
                            <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="list-group-item <?php echo e(is_null($notification->read_at) ? 'bg-light' : ''); ?>">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <p class="mb-1"><?php echo e($notification->data['message'] ?? $notification->data); ?></p>
                                            <small class="text-muted">
                                                <?php echo e($notification->created_at->diffForHumans()); ?>

                                            </small>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <?php if(is_null($notification->read_at)): ?>
                                                <form action="<?php echo e(route('notifications.markAsRead', $notification->id)); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        Marquer comme lu
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                            <form action="<?php echo e(route('notifications.destroy', $notification->id)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <?php if($notifications->hasPages()): ?>
                            <div class="modern-card-footer">
                                <div class="small text-muted">
                                    Affichage de <?php echo e($notifications->firstItem()); ?> à <?php echo e($notifications->lastItem()); ?> 
                                    sur <?php echo e($notifications->total()); ?> notification<?php echo e($notifications->total() > 1 ? 's' : ''); ?>

                                </div>
                                <div class="pagination-modern">
                                    <?php echo e($notifications->appends(request()->query())->links()); ?>

                                </div>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <p class="text-muted mb-0">Aucune notification</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\notifications\index.blade.php ENDPATH**/ ?>