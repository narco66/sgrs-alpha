@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Nouvelle délégation</h4>
        <div class="small">
            <a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Accueil</a>
            <span class="text-muted">/</span>
            <a href="{{ route('delegations.index') }}" class="text-decoration-none text-muted">Délégations</a>
            <span class="text-muted">/</span>
            <span class="text-muted">Nouvelle</span>
        </div>
        <p class="text-muted mb-0 mt-1">Créez une nouvelle délégation pour les États membres de la CEEAC.</p>
    </div>
    <a href="{{ route('delegations.index') }}" class="btn btn-outline-secondary">
        Retour à la liste
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        @include('partials.alerts')

        <form method="POST" action="{{ route('delegations.store') }}">
            @include('delegations._form')

            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('delegations.index') }}" class="btn btn-outline-secondary">
                    Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

