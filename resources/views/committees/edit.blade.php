@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Modifier le comité</h4>
        <p class="text-muted mb-0">
            {{ $committee->name }} ({{ $committee->code }})
        </p>
    </div>
    <a href="{{ route('committees.index') }}" class="btn btn-outline-secondary">
        Retour à la liste
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        @include('partials.alerts')

        <form method="POST" action="{{ route('committees.update', $committee) }}">
            @method('PUT')
            @include('committees._form')

            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('committees.index') }}" class="btn btn-outline-secondary">
                    Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
