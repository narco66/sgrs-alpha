@php
    $title = 'Connexion';
@endphp

@extends('layouts.auth')

@section('content')
<div class="d-flex flex-column gap-3" id="loginPanel">
    <div>
        <h2 class="auth-title mb-2">Connexion</h2>
        <p class="auth-subtitle">Acc&eacute;dez au syst&egrave;me avec vos identifiants SGRS-CEEAC</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger d-flex align-items-start gap-2" role="alert" aria-live="assertive">
            <i class="bi bi-exclamation-triangle mt-1"></i>
            <div>
                <strong>Erreur de connexion</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if (session('status'))
        <div class="alert alert-success d-flex align-items-center gap-2" role="status" aria-live="polite">
            <i class="bi bi-check-circle"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" id="loginForm" class="d-flex flex-column gap-4" novalidate>
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="form-label">
                <i class="bi bi-envelope me-1"></i> Adresse email
            </label>
            <input
                type="email"
                class="form-control @error('email') is-invalid @enderror"
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="utilisateur@email.com"
                required
                autofocus
                autocomplete="username"
            >
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Mot de passe --}}
        <div>
            <label for="password" class="form-label">
                <i class="bi bi-lock me-1"></i> Mot de passe
            </label>
            <div class="position-relative">
                <input
                    type="password"
                    class="form-control @error('password') is-invalid @enderror"
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
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Se souvenir de moi --}}
        <div class="d-flex align-items-center justify-content-between gap-2">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember" name="remember" @checked(old('remember'))>
                <label class="form-check-label" for="remember">Se souvenir de moi</label>
            </div>
            @if (Route::has('password.request'))
                <a class="small text-decoration-none" href="{{ route('password.request') }}">
                    <i class="bi bi-question-circle me-1"></i> Mot de passe oubli&eacute; ?
                </a>
            @endif
        </div>

        {{-- Bouton de connexion --}}
        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-box-arrow-in-right me-2"></i> Se connecter
            </button>
        </div>

        {{-- Liens --}}
        @if (Route::has('register'))
            <div class="auth-links">
                <span class="text-muted">Pas encore de compte ?</span>
                <a href="{{ route('register') }}">Cr&eacute;er un compte</a>
            </div>
        @endif
    </form>
</div>

@push('scripts')
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
@endpush
@endsection
