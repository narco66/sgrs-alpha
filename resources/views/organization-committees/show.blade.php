@extends('layouts.app')

@section('title', $organizationCommittee->name)

@section('content')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
        <li class="breadcrumb-item"><a href="{{ route('organization-committees.index') }}">Comités d'organisation</a></li>
        <li class="breadcrumb-item active">{{ Str::limit($organizationCommittee->name, 30) }}</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold">{{ $organizationCommittee->name }}</h3>
        <p class="text-muted mb-0 small">Accueil / Comités d'organisation / Détails</p>
    </div>
    <div>
        <a href="{{ route('organization-committees.pdf', $organizationCommittee) }}" class="btn btn-outline-primary">
            <i class="bi bi-file-earmark-pdf me-1"></i> PDF
        </a>
        @can('update', $organizationCommittee)
            <a href="{{ route('organization-committees.edit', $organizationCommittee) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil me-1"></i> Modifier
            </a>
        @endcan
        @can('delete', $organizationCommittee)
            <form action="{{ route('organization-committees.destroy', $organizationCommittee) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Êtes-vous sûr ?')">
                    <i class="bi bi-trash me-1"></i> Supprimer
                </button>
            </form>
        @endcan
    </div>
</div>

@include('partials.alerts')

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Informations du comité</h5>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-3">Nom</dt>
                    <dd class="col-sm-9">{{ $organizationCommittee->name }}</dd>
                    
                    <dt class="col-sm-3">Description</dt>
                    <dd class="col-sm-9">{{ $organizationCommittee->description ?? 'Aucune description' }}</dd>
                    
                    <dt class="col-sm-3">Réunion associée</dt>
                    <dd class="col-sm-9">
                        @if($organizationCommittee->meeting)
                            <a href="{{ route('meetings.show', $organizationCommittee->meeting) }}">
                                {{ $organizationCommittee->meeting->title }}
                            </a>
                        @else
                            <span class="text-muted">Aucune</span>
                        @endif
                    </dd>
                    
                    <dt class="col-sm-3">Créé par</dt>
                    <dd class="col-sm-9">{{ $organizationCommittee->creator->name ?? 'N/A' }}</dd>
                    
                    <dt class="col-sm-3">Date de création</dt>
                    <dd class="col-sm-9">{{ $organizationCommittee->created_at->format('d/m/Y H:i') }}</dd>
                    
                    <dt class="col-sm-3">Statut</dt>
                    <dd class="col-sm-9">
                        @if($organizationCommittee->is_active)
                            <span class="badge bg-success">Actif</span>
                        @else
                            <span class="badge bg-secondary">Inactif</span>
                        @endif
                    </dd>
                </dl>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Membres du comité ({{ $organizationCommittee->members->count() }})</h5>
            </div>
            <div class="card-body">
                @if($organizationCommittee->members->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Utilisateur</th>
                                    <th>Rôle</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($organizationCommittee->members as $member)
                                    <tr>
                                        <td>{{ $member->user->name }}</td>
                                        <td><span class="badge bg-primary">{{ $member->role }}</span></td>
                                        <td>{{ $member->notes ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">Aucun membre dans ce comité.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

