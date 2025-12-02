@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">{{ $meetingType->name }}</h4>
        <p class="text-muted mb-0">
            Type de réunion • Code : {{ $meetingType->code }}
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('meeting-types.edit', $meetingType) }}" class="btn btn-outline-secondary">
            <i class="bi bi-pencil me-1"></i> Modifier
        </a>
        <a href="{{ route('meeting-types.index') }}" class="btn btn-outline-secondary">
            Retour
        </a>
    </div>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-md-3">Code</dt>
            <dd class="col-md-9">
                <span class="badge bg-light text-dark border">{{ $meetingType->code }}</span>
            </dd>

            <dt class="col-md-3">Statut</dt>
            <dd class="col-md-9">
                @if($meetingType->is_active)
                    <span class="badge bg-success-subtle text-success-emphasis">Actif</span>
                @else
                    <span class="badge bg-secondary-subtle text-secondary-emphasis">Inactif</span>
                @endif
            </dd>

            <dt class="col-md-3">Approbations</dt>
            <dd class="col-md-9 small">
                @if($meetingType->requires_president_approval)
                    <span class="badge bg-outline border border-danger text-danger me-1">
                        Présidence
                    </span>
                @endif
                @if($meetingType->requires_sg_approval)
                    <span class="badge bg-outline border border-primary text-primary me-1">
                        Secrétariat Général
                    </span>
                @endif
                @if(!$meetingType->requires_president_approval && !$meetingType->requires_sg_approval)
                    <span class="text-muted">Aucune approbation spécifique</span>
                @endif
            </dd>

            <dt class="col-md-3">Ordre d'affichage</dt>
            <dd class="col-md-9">
                {{ $meetingType->sort_order }}
            </dd>

            <dt class="col-md-3">Description</dt>
            <dd class="col-md-9">
                {{ $meetingType->description ?: '—' }}
            </dd>
        </dl>
    </div>
</div>
@endsection
