@php
    $title = 'Vérification de l\'email';
@endphp

@extends('layouts.auth')

@section('content')
<div>
    <h2 class="auth-title">Vérification de l'email</h2>
    <p class="auth-subtitle">Merci de vous être inscrit ! Veuillez vérifier votre adresse email</p>

    <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>
        <strong>Email de vérification requis</strong>
        <p class="mb-0 mt-2">
            Avant de commencer, veuillez vérifier votre adresse email en cliquant sur le lien que nous venons de vous envoyer. 
            Si vous n'avez pas reçu l'email, nous pouvons vous en envoyer un autre.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success">
            <i class="bi bi-check-circle me-2"></i>
            <strong>Email envoyé</strong>
            <p class="mb-0 mt-2">
                Un nouveau lien de vérification a été envoyé à l'adresse email que vous avez fournie lors de l'inscription.
            </p>
        </div>
    @endif

    <div class="d-grid gap-2">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary btn-lg w-100">
                <i class="bi bi-envelope-check me-2"></i>
                Renvoyer l'email de vérification
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-secondary w-100">
                <i class="bi bi-box-arrow-right me-2"></i>
                Se déconnecter
            </button>
        </form>
    </div>
</div>
@endsection
