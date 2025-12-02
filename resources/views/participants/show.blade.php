@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">{{ $participant->full_name }}</h4>
        <div class="small">
            <a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Accueil</a>
            <span class="text-muted">/</span>
            <a href="{{ route('participants.index') }}" class="text-decoration-none text-muted">Participants</a>
            <span class="text-muted">/</span>
            <span class="text-muted">{{ $participant->full_name }}</span>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('participants.edit', $participant) }}" class="btn btn-outline-primary">
            <i class="bi bi-pencil me-1"></i> Modifier
        </a>
        <a href="{{ route('participants.index') }}" class="btn btn-outline-secondary">
            Retour
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h6 class="mb-0">Informations</h6>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-md-4">Nom complet</dt>
                    <dd class="col-md-8">{{ $participant->full_name }}</dd>

                    <dt class="col-md-4">Email</dt>
                    <dd class="col-md-8">
                        @if($participant->email)
                            <a href="mailto:{{ $participant->email }}">{{ $participant->email }}</a>
                        @else
                            <span class="text-muted">Non renseigné</span>
                        @endif
                    </dd>

                    <dt class="col-md-4">Téléphone</dt>
                    <dd class="col-md-8">{{ $participant->phone ?? 'Non renseigné' }}</dd>

                    <dt class="col-md-4">Fonction</dt>
                    <dd class="col-md-8">{{ $participant->position ?? 'Non renseignée' }}</dd>

                    <dt class="col-md-4">Institution</dt>
                    <dd class="col-md-8">{{ $participant->institution ?? 'Non renseignée' }}</dd>

                    <dt class="col-md-4">Pays</dt>
                    <dd class="col-md-8">{{ $participant->country ?? 'Non renseigné' }}</dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h6 class="mb-0">Statut</h6>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    @if($participant->is_internal)
                        <span class="badge-modern badge-modern-primary">Interne</span>
                    @else
                        <span class="badge-modern badge-modern-info">Externe</span>
                    @endif
                </p>
                <p class="mb-3">
                    @if($participant->is_active)
                        <span class="badge-modern badge-modern-success">Actif</span>
                    @else
                        <span class="badge-modern badge-modern-secondary">Inactif</span>
                    @endif
                </p>
                <p class="text-muted small mb-0">
                    Créé le {{ $participant->created_at?->format('d/m/Y H:i') ?? 'N/A' }}<br>
                    Mis à jour le {{ $participant->updated_at?->format('d/m/Y H:i') ?? 'N/A' }}
                </p>
            </div>
        </div>

        <div class="card shadow-sm border-0 mt-3">
            <div class="card-header bg-white">
                <h6 class="mb-0">Réunions associées</h6>
            </div>
            <div class="card-body">
                @php $meetings = $participant->meetings()->latest('start_at')->take(5)->get(); @endphp
                @forelse($meetings as $meeting)
                    <div class="mb-2">
                        <a href="{{ route('meetings.show', $meeting) }}" class="fw-semibold text-decoration-none">
                            {{ $meeting->title }}
                        </a>
                        <div class="text-muted small">
                            {{ $meeting->start_at ? \Carbon\Carbon::parse($meeting->start_at)->format('d/m/Y H:i') : 'Date à confirmer' }}
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">Aucune réunion liée.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
