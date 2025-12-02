@php
    $title = 'Connexion';
@endphp

@extends('layouts.auth')

@section('content')
<div>
    <h2 class="auth-title">Connexion</h2>
    <p class="auth-subtitle">Connectez-vous à votre compte pour accéder au système</p>

    @if ($errors->any())
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Erreur de connexion</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('status'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" id="loginForm">
        @csrf

        {{-- Email --}}
        <div class="mb-4">
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
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- Mot de passe --}}
        <div class="mb-4">
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
                >
                    <i class="bi bi-eye" id="togglePasswordIcon"></i>
                </button>
            </div>
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- Se souvenir de moi --}}
        <div class="mb-4">
            <div class="form-check">
                <input 
                    class="form-check-input" 
                    type="checkbox" 
                    id="remember" 
                    name="remember"
                >
                <label class="form-check-label" for="remember">
                    Se souvenir de moi
                </label>
            </div>
        </div>

        {{-- Bouton de connexion --}}
        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-box-arrow-in-right me-2"></i>
                Se connecter
            </button>
        </div>

        {{-- Liens --}}
        <div class="auth-links">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">
                    <i class="bi bi-question-circle me-1"></i>
                    Mot de passe oublié ?
                </a>
            @endif
        </div>

        @if (Route::has('register'))
            <div class="auth-links mt-2">
                <span class="text-muted">Vous n'avez pas encore de compte ?</span>
                <a href="{{ route('register') }}">
                    Inscrivez-vous
                </a>
            </div>
        @endif
    </form>
</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('togglePasswordIcon');
        
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
        const form = document.getElementById('loginForm');
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
