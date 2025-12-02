@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">{{ $delegation->title }}</h4>
        <div class="small">
            <a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Accueil</a>
            <span class="text-muted">/</span>
            <a href="{{ route('delegations.index') }}" class="text-decoration-none text-muted">Délégations</a>
        </div>
        <p class="text-muted mb-0 mt-1">
            Délégation
            @if($delegation->code)
                • Code : {{ $delegation->code }}
            @endif
            @if($delegation->country)
                • {{ $delegation->country }}
            @endif
        </p>
    </div>
    <div class="d-flex gap-2">
        @can('update', $delegation)
        <a href="{{ route('delegations.edit', $delegation) }}" class="btn btn-outline-secondary">
            <i class="bi bi-pencil me-1"></i> Modifier
        </a>
        @endcan
        <a href="{{ route('delegations.pdf', $delegation) }}" class="btn btn-outline-primary">
            <i class="bi bi-file-earmark-pdf"></i> PDF
        </a>
        <a href="{{ route('delegations.index') }}" class="btn btn-outline-secondary">
            Retour
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-white">
                <h5 class="mb-0">Informations générales</h5>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-md-4">Titre</dt>
                    <dd class="col-md-8">{{ $delegation->title }}</dd>

                    @if($delegation->code)
                    <dt class="col-md-4">Code</dt>
                    <dd class="col-md-8">
                        <span class="badge bg-light text-dark border">{{ $delegation->code }}</span>
                    </dd>
                    @endif

                    @if($delegation->country)
                    <dt class="col-md-4">Pays</dt>
                    <dd class="col-md-8">{{ $delegation->country }}</dd>
                    @endif

                    <dt class="col-md-4">Réunion associée</dt>
                    <dd class="col-md-8">
                        @if($delegation->meeting)
                            <a href="{{ route('meetings.show', $delegation->meeting) }}">
                                {{ $delegation->meeting->title }}
                            </a>
                            @if($delegation->meeting->start_at)
                                <div class="small text-muted">{{ $delegation->meeting->start_at->format('d/m/Y H:i') }}</div>
                            @endif
                        @else
                            <span class="text-muted">Non renseignée</span>
                        @endif
                    </dd>

                    <dt class="col-md-4">Statut</dt>
                    <dd class="col-md-8">
                        @if($delegation->is_active)
                            <span class="badge bg-success-subtle text-success-emphasis">
                                Actif
                            </span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary-emphasis">
                                Inactif
                            </span>
                        @endif
                    </dd>

                    @if($delegation->description)
                    <dt class="col-md-4">Description</dt>
                    <dd class="col-md-8">{{ $delegation->description }}</dd>
                    @endif

                    @if($delegation->contact_email)
                    <dt class="col-md-4">Email de contact</dt>
                    <dd class="col-md-8">
                        <a href="mailto:{{ $delegation->contact_email }}">{{ $delegation->contact_email }}</a>
                    </dd>
                    @endif

                    @if($delegation->contact_phone)
                    <dt class="col-md-4">Téléphone</dt>
                    <dd class="col-md-8">{{ $delegation->contact_phone }}</dd>
                    @endif

                    @if($delegation->address)
                    <dt class="col-md-4">Adresse</dt>
                    <dd class="col-md-8">{{ $delegation->address }}</dd>
                    @endif
                </dl>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0">Utilisateurs</h5>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <strong>{{ $delegation->users->count() }}</strong> utilisateur(s) associé(s)
                </p>
                @if($delegation->users->count() > 0)
                    <ul class="list-unstyled mb-0">
                        @foreach($delegation->users as $user)
                            <li class="mb-2">
                                <a href="{{ route('users.show', $user) }}" class="text-decoration-none">
                                    {{ $user->name }}
                                </a>
                                @if($user->email)
                                    <div class="small text-muted">{{ $user->email }}</div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted small mb-0">Aucun utilisateur associé</p>
                @endif
            </div>
        </div>
        <div class="card shadow-sm border-0 mt-3">
            <div class="card-header bg-white">
                <h5 class="mb-0">Participants de la délégation</h5>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <strong>{{ $delegation->participants->count() }}</strong> participant(s) associés
                </p>
                @if($delegation->participants->count() > 0)
                    <ul class="list-unstyled mb-0">
                        @foreach($delegation->participants as $user)
                            <li class="mb-2">
                                <a href="{{ route('users.show', $user) }}" class="text-decoration-none">
                                    {{ $user->name }}
                                </a>
                                @if($user->email)
                                    <div class="small text-muted">{{ $user->email }}</div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted small mb-0">Aucun participant associé</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

