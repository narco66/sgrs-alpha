@php
    $title = 'Réinitialiser le mot de passe';
@endphp

@extends('layouts.auth')

@section('content')
<div>
    <h2 class="auth-title">Réinitialiser le mot de passe</h2>
    <p class="auth-subtitle">Entrez votre nouveau mot de passe</p>

    @if ($errors->any())
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Erreurs de validation</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}" id="resetPasswordForm">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">
                <i class="bi bi-envelope me-1"></i> Adresse email
            </label>
            <input 
                type="email" 
                class="form-control @error('email') is-invalid @enderror" 
                id="email" 
                name="email" 
                value="{{ old('email', $request->email) }}" 
                required 
                autofocus 
                autocomplete="username"
                readonly
            >
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">
                <i class="bi bi-lock me-1"></i> Nouveau mot de passe
            </label>
            <div class="position-relative">
                <input 
                    type="password" 
                    class="form-control @error('password') is-invalid @enderror" 
                    id="password" 
                    name="password" 
                    required 
                    autocomplete="new-password"
                >
                <button 
                    type="button" 
                    class="btn btn-link position-absolute end-0 top-50 translate-middle-y pe-3" 
                    style="border: none; background: none; color: #64748b;"
                    onclick="togglePassword('password')"
                >
                    <i class="bi bi-eye" id="togglePasswordIcon"></i>
                </button>
            </div>
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
            <small class="text-muted">Minimum 8 caractères</small>
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="form-label">
                <i class="bi bi-lock-fill me-1"></i> Confirmer le nouveau mot de passe
            </label>
            <div class="position-relative">
                <input 
                    type="password" 
                    class="form-control" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    required 
                    autocomplete="new-password"
                >
                <button 
                    type="button" 
                    class="btn btn-link position-absolute end-0 top-50 translate-middle-y pe-3" 
                    style="border: none; background: none; color: #64748b;"
                    onclick="togglePassword('password_confirmation')"
                >
                    <i class="bi bi-eye" id="togglePasswordConfirmationIcon"></i>
                </button>
            </div>
        </div>

        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-key me-2"></i>
                Réinitialiser le mot de passe
            </button>
        </div>

        <div class="auth-links">
            <a href="{{ route('login') }}">
                <i class="bi bi-arrow-left me-1"></i>
                Retour à la connexion
            </a>
        </div>
    </form>
</div>

<script>
    function togglePassword(fieldId) {
        const passwordInput = document.getElementById(fieldId);
        const iconId = fieldId === 'password' ? 'togglePasswordIcon' : 'togglePasswordConfirmationIcon';
        const toggleIcon = document.getElementById(iconId);
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('bi-eye');
            toggleIcon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('bi-eye-slash');
            toggleIcon.classList.add('bi-eye');
        }
    }

    // Animation au chargement
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('resetPasswordForm');
        form.style.opacity = '0';
        form.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            form.style.transition = 'all 0.5s ease';
            form.style.opacity = '1';
            form.style.transform = 'translateY(0)';
        }, 100);
    });
</script>
@endsection
