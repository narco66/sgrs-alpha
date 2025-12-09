<?php $__env->startSection('title', 'Nouvelle salle de réunion'); ?>

<?php $__env->startSection('content'); ?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('dashboard')); ?>">Accueil</a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('rooms.index')); ?>">Salles de réunion</a>
        </li>
        <li class="breadcrumb-item active">Nouvelle salle</li>
    </ol>
</nav>


<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="page-title mb-1">Nouvelle salle de réunion</h3>
        <p class="text-muted mb-0 small">Ajoutez une salle conforme au modèle institutionnel CEEAC.</p>
    </div>
    <a href="<?php echo e(route('rooms.index')); ?>" class="btn btn-modern btn-modern-secondary">
        <i class="bi bi-arrow-left"></i>
        Retour à la liste
    </a>
</div>


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
        <strong><i class="bi bi-exclamation-triangle me-2"></i>Erreurs détectées :</strong>
        <ul class="mb-0 mt-2">
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

<form method="POST" action="<?php echo e(route('rooms.store')); ?>" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>

    <div class="row g-4">
        
        <div class="col-lg-8">
            
            <div class="modern-card mb-4">
                <div class="modern-card-header">
                    <h5 class="modern-card-title">
                        <i class="bi bi-door-open text-primary me-2"></i>
                        Informations de la salle
                    </h5>
                </div>
                <div class="modern-card-body">
                    <div class="row g-3">
                        
                        <div class="col-md-8">
                            <label class="form-label">
                                Nom de la salle
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="name"
                                   class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   placeholder="Ex : Salle de Conférence du 5ème étage"
                                   value="<?php echo e(old('name')); ?>"
                                   required>
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

                        
                        <div class="col-md-4">
                            <label class="form-label">
                                Code
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="code"
                                   class="form-control <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   placeholder="Ex : SC-05"
                                   value="<?php echo e(old('code')); ?>"
                                   required>
                            <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">
                                Code unique pour identifier la salle (sera enregistré en majuscules).
                            </div>
                        </div>

                        
                        <div class="col-md-8">
                            <label class="form-label">Localisation</label>
                            <input type="text"
                                   name="location"
                                   class="form-control <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   placeholder="Ex : Bâtiment principal, 5ème étage"
                                   value="<?php echo e(old('location')); ?>">
                            <?php $__errorArgs = ['location'];
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

                        
                        <div class="col-md-4">
                            <label class="form-label">
                                Capacité
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number"
                                       name="capacity"
                                       class="form-control <?php $__errorArgs = ['capacity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       placeholder="50"
                                       min="1"
                                       max="1000"
                                       value="<?php echo e(old('capacity')); ?>"
                                       required>
                                <span class="input-group-text">personnes</span>
                            </div>
                            <?php $__errorArgs = ['capacity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description"
                                      class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                      rows="3"
                                      placeholder="Description de la salle, caractéristiques particulières..."><?php echo e(old('description')); ?></textarea>
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
                    </div>
                </div>
            </div>

            
            <div class="modern-card">
                <div class="modern-card-header">
                    <h5 class="modern-card-title">
                        <i class="bi bi-tools text-primary me-2"></i>
                        Équipements disponibles
                    </h5>
                </div>
                <div class="modern-card-body">
                    <p class="text-muted small mb-3">
                        Sélectionnez les équipements présents dans la salle. Ils seront réutilisés dans les documents logistiques.
                    </p>
                    <div class="row g-3">
                        <?php
                            $selectedEquipments = old('equipments', []);
                        ?>
                        <?php $__currentLoopData = $equipmentOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="equipments[]"
                                           value="<?php echo e($value); ?>"
                                           id="equip_<?php echo e($value); ?>"
                                           <?php if(in_array($value, $selectedEquipments)): echo 'checked'; endif; ?>>
                                    <label class="form-check-label" for="equip_<?php echo e($value); ?>">
                                        <?php echo e($label); ?>

                                    </label>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-lg-4">
            
            <div class="modern-card mb-4">
                <div class="modern-card-header">
                    <h5 class="modern-card-title">
                        <i class="bi bi-image text-primary me-2"></i>
                        Photo de la salle
                    </h5>
                </div>
                <div class="modern-card-body">
                    <div class="mb-3">
                        
                        <div id="imagePreview"
                             class="border rounded mb-3 d-flex align-items-center justify-content-center bg-light"
                             style="height: 190px; overflow: hidden;">
                            <div class="text-center text-muted">
                                <i class="bi bi-image fs-1"></i>
                                <p class="small mb-0">Aucune image sélectionnée</p>
                            </div>
                        </div>

                        <input type="file"
                               name="image"
                               class="form-control <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               accept="image/jpeg,image/png,image/jpg,image/webp"
                               id="imageInput">
                        <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>>
                        <div class="form-text">
                            Formats acceptés : JPEG, PNG, WebP &mdash; taille maximale 5 Mo.
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="modern-card mb-4">
                <div class="modern-card-header">
                    <h5 class="modern-card-title">
                        <i class="bi bi-toggle-on text-primary me-2"></i>
                        Statut de la salle
                    </h5>
                </div>
                <div class="modern-card-body">
                    <div class="form-check form-switch">
                        <input class="form-check-input"
                               type="checkbox"
                               name="is_active"
                               id="is_active"
                               value="1"
                               <?php if(old('is_active', true)): echo 'checked'; endif; ?>>
                        <label class="form-check-label ms-2" for="is_active">
                            <strong>Salle active</strong>
                            <br>
                            <small class="text-muted">
                                Une salle active peut être réservée pour des réunions et apparaît dans les listes de sélection.
                            </small>
                        </label>
                    </div>
                </div>
            </div>

            
            <div class="modern-card">
                <div class="modern-card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-modern btn-modern-primary">
                            <i class="bi bi-check-circle me-1"></i>
                            Créer la salle
                        </button>
                        <a href="<?php echo e(route('rooms.index')); ?>" class="btn btn-modern btn-modern-secondary">
                            <i class="bi bi-x-circle me-1"></i>
                            Annuler
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');

    if (!imageInput || !imagePreview) {
        return;
    }

    imageInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) {
            imagePreview.innerHTML = `
                <div class="text-center text-muted">
                    <i class="bi bi-image fs-1"></i>
                    <p class="small mb-0">Aucune image sélectionnée</p>
                </div>
            `;
            return;
        }

        const reader = new FileReader();
        reader.onload = function (ev) {
            imagePreview.innerHTML = `
                <img src="${ev.target.result}"
                     alt="Prévisualisation de la salle"
                     class="img-fluid rounded"
                     style="max-height: 190px; width: 100%; object-fit: cover;">
            `;
        };
        reader.readAsDataURL(file);
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views/rooms/create.blade.php ENDPATH**/ ?>