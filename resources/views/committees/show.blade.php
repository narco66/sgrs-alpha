@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">{{ $committee->name }}</h4>
        <p class="text-muted mb-0">
            Comité • Code : {{ $committee->code }}
        </p>
    </div>
    <div class="d-flex gap-2">
        @can('update', $committee)
        <a href="{{ route('committees.edit', $committee) }}" class="btn btn-outline-secondary">
            <i class="bi bi-pencil me-1"></i> Modifier
        </a>
        @endcan
        <a href="{{ route('committees.index') }}" class="btn btn-outline-secondary">
            Retour
        </a>
    </div>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-md-3">Code</dt>
            <dd class="col-md-9">
                <span class="badge bg-light text-dark border">{{ $committee->code }}</span>
            </dd>

            <dt class="col-md-3">Type de réunion associé</dt>
            <dd class="col-md-9">
                @if($committee->meetingType)
                    {{ $committee->meetingType->name }} ({{ $committee->meetingType->code }})
                @else
                    <span class="text-muted">Non défini</span>
                @endif
            </dd>

            <dt class="col-md-3">Nature</dt>
            <dd class="col-md-9">
                @if($committee->is_permanent)
                    <span class="badge bg-info-subtle text-info-emphasis">
                        Permanent
                    </span>
                @else
                    <span class="badge bg-secondary-subtle text-secondary-emphasis">
                        Ad hoc
                    </span>
                @endif
            </dd>

            <dt class="col-md-3">Statut</dt>
            <dd class="col-md-9">
                @if($committee->is_active)
                    <span class="badge bg-success-subtle text-success-emphasis">
                        Actif
                    </span>
                @else
                    <span class="badge bg-secondary-subtle text-secondary-emphasis">
                        Inactif
                    </span>
                @endif
            </dd>

            <dt class="col-md-3">Ordre d'affichage</dt>
            <dd class="col-md-9">
                {{ $committee->sort_order }}
            </dd>

            <dt class="col-md-3">Description</dt>
            <dd class="col-md-9">
                {{ $committee->description ?: '—' }}
            </dd>
        </dl>
    </div>
</div>
@endsection
