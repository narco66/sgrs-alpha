@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Modifier le type de réunion</h4>
        <p class="text-muted mb-0">
            {{ $meetingType->name }} ({{ $meetingType->code }})
        </p>
    </div>
    <a href="{{ route('meeting-types.index') }}" class="btn btn-outline-secondary">
        Retour à la liste
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        @include('partials.alerts')

        <form method="POST" action="{{ route('meeting-types.update', $meetingType) }}">
            @method('PUT')
            @include('meeting_types._form')

            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('meeting-types.index') }}" class="btn btn-outline-secondary">
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
