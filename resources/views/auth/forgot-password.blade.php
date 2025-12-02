@php
    $title = 'Mot de passe oublié';
@endphp

@extends('layouts.auth')

@section('content')
<div>
    <h2 class="auth-title">Mot de passe oublié</h2>
    <p class="auth-subtitle">Entrez votre adresse email et nous vous enverrons un lien de réinitialisation</p>

    @if (session('status'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Erreur</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" id="forgotPasswordForm">
        @csrf

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
                autocomplete="email"
            >
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
            <small class="text-muted">
                <i class="bi bi-info-circle me-1"></i>
                Un lien de réinitialisation vous sera envoyé par email.
            </small>
        </div>

        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-send me-2"></i>
                Envoyer le lien de réinitialisation
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
    // Animation au chargement
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('forgotPasswordForm');
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
