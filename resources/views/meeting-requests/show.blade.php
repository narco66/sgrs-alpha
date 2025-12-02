@extends('layouts.app')

@section('title', $meetingRequest->title)

@section('content')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
        <li class="breadcrumb-item"><a href="{{ route('meeting-requests.index') }}">Demandes de réunion</a></li>
        <li class="breadcrumb-item active">{{ Str::limit($meetingRequest->title, 30) }}</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold">{{ $meetingRequest->title }}</h3>
        <p class="text-muted mb-0 small">Accueil / Demandes de réunion / Détails</p>
    </div>
    <div>
        @php
            $statusClass = match($meetingRequest->status) {
                'pending' => 'bg-warning text-dark',
                'approved' => 'bg-success',
                'rejected' => 'bg-danger',
                default => 'bg-secondary'
            };
        @endphp
        <span class="badge {{ $statusClass }} fs-6">{{ ucfirst($meetingRequest->status) }}</span>
    </div>
</div>

@include('partials.alerts')

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Détails de la demande</h5>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-3">Titre</dt>
                    <dd class="col-sm-9">{{ $meetingRequest->title }}</dd>
                    
                    <dt class="col-sm-3">Description</dt>
                    <dd class="col-sm-9">{{ $meetingRequest->description ?? 'Aucune description' }}</dd>
                    
                    <dt class="col-sm-3">Type</dt>
                    <dd class="col-sm-9">{{ $meetingRequest->meetingType->name ?? '—' }}</dd>
                    
                    <dt class="col-sm-3">Comité</dt>
                    <dd class="col-sm-9">{{ $meetingRequest->committee->name ?? '—' }}</dd>
                    
                    <dt class="col-sm-3">Date demandée</dt>
                    <dd class="col-sm-9">{{ $meetingRequest->requested_start_at->format('d/m/Y H:i') }}</dd>
                    
                    <dt class="col-sm-3">Salle demandée</dt>
                    <dd class="col-sm-9">{{ $meetingRequest->requestedRoom->name ?? ($meetingRequest->other_location ?? '—') }}</dd>
                    
                    <dt class="col-sm-3">Justification</dt>
                    <dd class="col-sm-9">{{ $meetingRequest->justification ?? '—' }}</dd>
                    
                    <dt class="col-sm-3">Demandeur</dt>
                    <dd class="col-sm-9">{{ $meetingRequest->requester->name }}</dd>
                    
                    @if($meetingRequest->reviewed_by)
                        <dt class="col-sm-3">Examiné par</dt>
                        <dd class="col-sm-9">{{ $meetingRequest->reviewer->name }}</dd>
                        
                        <dt class="col-sm-3">Date d'examen</dt>
                        <dd class="col-sm-9">{{ $meetingRequest->reviewed_at->format('d/m/Y H:i') }}</dd>
                        
                        <dt class="col-sm-3">Commentaires</dt>
                        <dd class="col-sm-9">{{ $meetingRequest->review_comments ?? '—' }}</dd>
                    @endif
                    
                    @if($meetingRequest->meeting)
                        <dt class="col-sm-3">Réunion créée</dt>
                        <dd class="col-sm-9">
                            <a href="{{ route('meetings.show', $meetingRequest->meeting) }}">
                                {{ $meetingRequest->meeting->title }}
                            </a>
                        </dd>
                    @endif
                </dl>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        @can('approve', $meetingRequest)
            @if($meetingRequest->status === 'pending')
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">Actions</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('meeting-requests.approve', $meetingRequest) }}" method="POST" class="mb-2">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label small">Commentaires (optionnel)</label>
                                <textarea name="review_comments" class="form-control form-control-sm" rows="2"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-check-circle me-1"></i> Approuver
                            </button>
                        </form>
                        
                        <form action="{{ route('meeting-requests.reject', $meetingRequest) }}" method="POST">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label small">Motif du rejet <span class="text-danger">*</span></label>
                                <textarea name="review_comments" class="form-control form-control-sm" rows="2" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-x-circle me-1"></i> Rejeter
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        @endcan
    </div>
</div>
@endsection

