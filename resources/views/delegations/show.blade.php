@extends('layouts.app')

@section('content')
@php
    $hasMembers = $delegation->members && $delegation->members->count() > 0;
    $hasHeadOfDelegation = !empty($delegation->head_of_delegation_name);
    $canGenerateBadges = $hasMembers || $hasHeadOfDelegation;
@endphp

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
        
        {{-- Menu déroulant pour les exports PDF --}}
        <div class="dropdown">
            <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-file-earmark-pdf me-1"></i> Exports PDF
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="{{ route('delegations.pdf', $delegation) }}">
                        <i class="bi bi-file-text me-2"></i> Fiche de la délégation
                    </a>
                </li>
                @if($canGenerateBadges)
                <li><hr class="dropdown-divider"></li>
                <li><h6 class="dropdown-header">Badges participants</h6></li>
                <li>
                    <a class="dropdown-item" href="{{ route('delegations.badges', $delegation) }}">
                        <i class="bi bi-person-badge me-2"></i> Tous les badges ({{ $hasMembers ? $delegation->members->count() : 0 }}{{ $hasHeadOfDelegation ? ' + Chef' : '' }})
                    </a>
                </li>
                @endif
            </ul>
        </div>
        
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
        {{-- Chef de Délégation --}}
        @if($hasHeadOfDelegation)
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-danger-subtle">
                <h5 class="mb-0 text-danger-emphasis">
                    <i class="bi bi-person-fill me-1"></i> Chef de Délégation
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-danger-subtle rounded-circle p-2 me-3">
                        <i class="bi bi-person-badge fs-4 text-danger"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $delegation->head_of_delegation_name }}</h6>
                        @if($delegation->head_of_delegation_position)
                            <small class="text-muted">{{ $delegation->head_of_delegation_position }}</small>
                        @endif
                        @if($delegation->head_of_delegation_email)
                            <div class="small">
                                <a href="mailto:{{ $delegation->head_of_delegation_email }}">
                                    {{ $delegation->head_of_delegation_email }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Membres de la délégation --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-people me-1"></i> Membres de la délégation
                </h5>
                @can('update', $delegation)
                <a href="{{ route('delegations.members.create', $delegation) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-plus"></i> Ajouter
                </a>
                @endcan
            </div>
            <div class="card-body">
                @if($hasMembers)
                    <p class="mb-3">
                        <strong>{{ $delegation->members->count() }}</strong> membre(s) enregistré(s)
                    </p>
                    <div class="list-group list-group-flush">
                        @foreach($delegation->members->sortBy(function($m) { return $m->role === 'head' ? 0 : 1; }) as $member)
                            <div class="list-group-item px-0 d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="fw-semibold">
                                        {{ $member->full_name }}
                                        @if($member->role === 'head')
                                            <span class="badge bg-danger-subtle text-danger-emphasis ms-1">Chef</span>
                                        @endif
                                    </div>
                                    @if($member->position)
                                        <small class="text-muted d-block">{{ $member->position }}</small>
                                    @endif
                                    @if($member->email)
                                        <small class="text-muted">{{ $member->email }}</small>
                                    @endif
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('delegations.members.badge', [$delegation, $member]) }}">
                                                <i class="bi bi-person-badge me-2"></i> Badge PDF
                                            </a>
                                        </li>
                                        @can('update', $delegation)
                                        <li>
                                            <a class="dropdown-item" href="{{ route('delegations.members.edit', [$delegation, $member]) }}">
                                                <i class="bi bi-pencil me-2"></i> Modifier
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('delegations.members.destroy', [$delegation, $member]) }}" method="POST" onsubmit="return confirm('Supprimer ce membre ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="bi bi-trash me-2"></i> Supprimer
                                                </button>
                                            </form>
                                        </li>
                                        @endcan
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($canGenerateBadges)
                    <div class="mt-3 pt-3 border-top">
                        <a href="{{ route('delegations.badges', $delegation) }}" class="btn btn-outline-primary btn-sm w-100">
                            <i class="bi bi-person-badge me-1"></i> Générer tous les badges PDF
                        </a>
                    </div>
                    @endif
                @else
                    <p class="text-muted mb-0">
                        Aucun membre enregistré.
                        @can('update', $delegation)
                        <a href="{{ route('delegations.members.create', $delegation) }}">Ajouter un membre</a>
                        @endcan
                    </p>
                    @if($hasHeadOfDelegation)
                    <div class="mt-3 pt-3 border-top">
                        <p class="small text-muted mb-2">
                            <i class="bi bi-info-circle"></i> Le Chef de Délégation peut générer un badge.
                        </p>
                        <a href="{{ route('delegations.badges', $delegation) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-person-badge me-1"></i> Badge du Chef de Délégation
                        </a>
                    </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

