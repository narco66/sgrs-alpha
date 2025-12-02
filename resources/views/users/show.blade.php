@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">{{ $user->name }}</h4>
        <p class="text-muted mb-0">
            Profil utilisateur
        </p>
    </div>
    <div class="btn-group">
        @can('update', $user)
        <a href="{{ route('users.edit', $user) }}" class="btn btn-outline-primary">
            <i class="bi bi-pencil me-1"></i> Modifier
        </a>
        @endcan
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
            Retour à la liste
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h5 class="mb-1">{{ $user->name }}</h5>
                @if($user->first_name || $user->last_name)
                    <p class="text-muted mb-2">{{ $user->first_name }} {{ $user->last_name }}</p>
                @endif
                <p class="text-muted mb-3">{{ $user->email }}</p>
                
                @if($user->is_active)
                    <span class="badge bg-success mb-3">Compte actif</span>
                @else
                    <span class="badge bg-danger mb-3">Compte inactif</span>
                @endif

                @can('toggleActive', $user)
                <form method="POST" action="{{ route('users.toggle-active', $user) }}" class="mt-3">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-{{ $user->is_active ? 'danger' : 'success' }}">
                        {{ $user->is_active ? 'Désactiver' : 'Activer' }} le compte
                    </button>
                </form>
                @endcan
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-white">
                <h6 class="mb-0">Informations personnelles</h6>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Email</dt>
                    <dd class="col-sm-8">{{ $user->email }}</dd>

                    @if($user->service)
                    <dt class="col-sm-4">Service</dt>
                    <dd class="col-sm-8">{{ $user->service }}</dd>
                    @endif

                    @if($user->delegation)
                    <dt class="col-sm-4">Délégation</dt>
                    <dd class="col-sm-8">
                        <a href="{{ route('delegations.show', $user->delegation) }}">
                            {{ $user->delegation->title }}
                        </a>
                    </dd>
                    @endif

                    <dt class="col-sm-4">Rôles</dt>
                    <dd class="col-sm-8">
                        @foreach($user->roles as $role)
                            <span class="badge bg-secondary me-1">{{ $role->name }}</span>
                        @endforeach
                    </dd>

                    <dt class="col-sm-4">Date d'inscription</dt>
                    <dd class="col-sm-8">{{ $user->created_at->format('d/m/Y H:i') }}</dd>

                    @if($user->email_verified_at)
                    <dt class="col-sm-4">Email vérifié</dt>
                    <dd class="col-sm-8">{{ $user->email_verified_at->format('d/m/Y H:i') }}</dd>
                    @endif
                </dl>
            </div>
        </div>

        @if($user->organizedMeetings->count() > 0)
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-white">
                <h6 class="mb-0">Réunions organisées ({{ $user->organizedMeetings->count() }})</h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @foreach($user->organizedMeetings->take(5) as $meeting)
                        @continue(!$meeting)
                        <a href="{{ route('meetings.show', $meeting) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-1">{{ $meeting->title }}</h6>
                                    <small class="text-muted">{{ $meeting->start_at->format('d/m/Y H:i') }}</small>
                                </div>
                                <span class="badge bg-{{ $meeting->status === 'terminee' ? 'success' : 'primary' }}">
                                    {{ $meeting->status }}
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
                @if($user->organizedMeetings->count() > 5)
                    <div class="mt-3 text-center">
                        <a href="{{ route('meetings.index', ['organizer' => $user->id]) }}" class="btn btn-sm btn-outline-primary">
                            Voir toutes les réunions
                        </a>
                    </div>
                @endif
            </div>
        </div>
        @endif

        @if($user->meetingParticipations->count() > 0)
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h6 class="mb-0">Participations aux réunions ({{ $user->meetingParticipations->count() }})</h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @foreach($user->meetingParticipations->take(5) as $participation)
                        @php $meeting = $participation->meeting; @endphp
                        @continue(!$meeting)
                        <a href="{{ route('meetings.show', $meeting) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-1">{{ $meeting->title }}</h6>
                                    <small class="text-muted">{{ $meeting->start_at->format('d/m/Y H:i') }}</small>
                                </div>
                                <span class="badge bg-{{ $participation->status === 'confirmed' ? 'success' : 'warning' }}">
                                    {{ $participation->status }}
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

