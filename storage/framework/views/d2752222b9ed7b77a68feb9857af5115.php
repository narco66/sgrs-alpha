<?php
    $title = 'Connexion';
?>



<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column gap-3" id="loginPanel">
    <div>
        <h2 class="auth-title mb-2">Connexion</h2>
        <p class="auth-subtitle">Acc&eacute;dez au syst&egrave;me avec vos identifiants SGRS-CEEAC</p>
    </div>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger d-flex align-items-start gap-2" role="alert" aria-live="assertive">
            <i class="bi bi-exclamation-triangle mt-1"></i>
            <div>
                <strong>Erreur de connexion</strong>
                <ul class="mb-0 mt-2">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <?php if(session('status')): ?>
        <div class="alert alert-success d-flex align-items-center gap-2" role="status" aria-live="polite">
            <i class="bi bi-check-circle"></i>
            <span><?php echo e(session('status')); ?></span>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('login')); ?>" id="loginForm" class="d-flex flex-column gap-4" novalidate>
        <?php echo csrf_field(); ?>

        
        <div>
            <label for="email" class="form-label">
                <i class="bi bi-envelope me-1"></i> Adresse email
            </label>
            <input
                type="email"
                class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                id="email"
                name="email"
                value="<?php echo e(old('email')); ?>"
                placeholder="utilisateur@email.com"
                required
                autofocus
                autocomplete="username"
            >
            <?php $__errorArgs = ['email'];
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

        
        <div>
            <label for="password" class="form-label">
                <i class="bi bi-lock me-1"></i> Mot de passe
            </label>
            <div class="position-relative">
                <input
                    type="password"
                    class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    id="password"
                    name="password"
                    placeholder="Mot de passe"
                    required
                    autocomplete="current-password"
                >
                <button
                    type="button"
                    class="btn btn-link position-absolute end-0 top-50 translate-middle-y pe-3"
                    style="border: none; background: none; color: #64748b;"
                    onclick="togglePassword()"
                    aria-label="Afficher ou masquer le mot de passe"
                >
                    <i class="bi bi-eye" id="togglePasswordIcon"></i>
                </button>
            </div>
            <?php $__errorArgs = ['password'];
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

        
        <div class="d-flex align-items-center justify-content-between gap-2">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember" name="remember" <?php if(old('remember')): echo 'checked'; endif; ?>>
                <label class="form-check-label" for="remember">Se souvenir de moi</label>
            </div>
            <?php if(Route::has('password.request')): ?>
                <a class="small text-decoration-none" href="<?php echo e(route('password.request')); ?>">
                    <i class="bi bi-question-circle me-1"></i> Mot de passe oubli&eacute; ?
                </a>
            <?php endif; ?>
        </div>

        
        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-box-arrow-in-right me-2"></i> Se connecter
            </button>
        </div>

        
        <?php if(Route::has('register')): ?>
            <div class="auth-links">
                <span class="text-muted">Pas encore de compte ?</span>
                <a href="<?php echo e(route('register')); ?>">Cr&eacute;er un compte</a>
            </div>
        <?php endif; ?>
    </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('togglePasswordIcon');
        const isHidden = passwordInput.type === 'password';

        passwordInput.type = isHidden ? 'text' : 'password';
        toggleIcon.classList.toggle('bi-eye', !isHidden);
        toggleIcon.classList.toggle('bi-eye-slash', isHidden);
    }

    document.addEventListener('DOMContentLoaded', function () {
        const panel = document.getElementById('loginPanel');
        const form = document.getElementById('loginForm');

        if (panel && form) {
            [panel, form].forEach((el, index) => {
                if (el) {
                    el.style.opacity = '0';
                    el.style.transform = 'translateY(18px)';

                    setTimeout(() => {
                        el.style.transition = 'all 0.45s ease';
                        el.style.opacity = '1';
                        el.style.transform = 'translateY(0)';
                    }, 100 + index * 60);
                }
            });
        } else {
            // Si les éléments ne sont pas trouvés, s'assurer qu'ils sont visibles
            if (panel) panel.style.opacity = '1';
            if (form) form.style.opacity = '1';
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\auth\login.blade.php ENDPATH**/ ?>