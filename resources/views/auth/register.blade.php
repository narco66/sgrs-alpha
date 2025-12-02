@php
    $title = 'Inscription';
@endphp

@extends('layouts.auth')

@section('content')
<div>
    <h2 class="auth-title">Créer un compte</h2>
    <p class="auth-subtitle">Inscrivez-vous pour accéder au système</p>

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

    <form method="POST" action="{{ route('register') }}" id="registerForm">
        @csrf

        {{-- Nom --}}
        <div class="mb-3">
            <label for="name" class="form-label">
                <i class="bi bi-person me-1"></i> Nom complet
            </label>
            <input 
                type="text" 
                class="form-control @error('name') is-invalid @enderror" 
                id="name" 
                name="name" 
                value="{{ old('name') }}" 
                placeholder="Votre nom complet"
                required 
                autofocus 
                autocomplete="name"
            >
            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- Email --}}
        <div class="mb-3">
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
                autocomplete="username"
            >
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- Mot de passe --}}
        <div class="mb-3">
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

        {{-- Confirmation mot de passe --}}
        <div class="mb-4">
            <label for="password_confirmation" class="form-label">
                <i class="bi bi-lock-fill me-1"></i> Confirmer le mot de passe
            </label>
            <div class="position-relative">
                <input 
                    type="password" 
                    class="form-control" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    placeholder="Confirmer le mot de passe"
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

        {{-- Bouton d'inscription --}}
        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-person-plus me-2"></i>
                Créer mon compte
            </button>
        </div>

        {{-- Lien de connexion --}}
        <div class="auth-links">
            <span class="text-muted">Vous avez déjà un compte ?</span>
            <a href="{{ route('login') }}">
                Connectez-vous
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
        const form = document.getElementById('registerForm');
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
