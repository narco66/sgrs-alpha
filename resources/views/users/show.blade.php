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
        @php
            $canAdminUsers = auth()->user()->hasAnyRole(['super-admin', 'admin', 'dsi'])
                || auth()->user()->can('users.manage');
        @endphp
        @if($canAdminUsers)
            <a href="{{ route('users.edit', $user) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil me-1"></i> Modifier
            </a>
        @endif
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

                @php
                    $status = $user->status ?? ($user->is_active ? 'active' : 'inactive');
                @endphp

                @if($status === 'active')
                    <span class="badge bg-success mb-3">Compte actif</span>
                @elseif($status === 'pending')
                    <span class="badge bg-warning text-dark mb-3">Compte en attente de validation</span>
                @elseif($status === 'rejected')
                    <span class="badge bg-danger mb-3">Compte rejeté</span>
                @else
                    <span class="badge bg-secondary mb-3">Compte inactif</span>
                @endif

                @can('toggleActive', $user)
                    @if($status === 'pending')
                        {{-- Actions spécifiques pour les comptes en attente --}}
                        <form method="POST" action="{{ route('users.approve', $user) }}" class="mt-3 d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="bi bi-check-circle me-1"></i> Valider le compte
                            </button>
                        </form>
                        <button type="button" class="btn btn-sm btn-outline-danger mt-3" data-bs-toggle="modal" data-bs-target="#rejectUserModal">
                            <i class="bi bi-x-circle me-1"></i> Rejeter le compte
                        </button>
                    @else
                        {{-- Bascule simple actif / inactif pour les comptes existants --}}
                        <form method="POST" action="{{ route('users.toggle-active', $user) }}" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-{{ $user->is_active ? 'danger' : 'success' }}">
                                {{ $user->is_active ? 'Désactiver' : 'Activer' }} le compte
                            </button>
                        </form>
                    @endif
                @endcan
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
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

        @if(isset($statusLogs) && $statusLogs->count() > 0)
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Historique du statut du compte</h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @foreach($statusLogs as $log)
                        @php
                            $oldStatus = $log->old_values['status'] ?? null;
                            $newStatus = $log->new_values['status'] ?? null;

                            $formatStatus = function (?string $s) {
                                return match($s) {
                                    'active'   => 'actif',
                                    'inactive' => 'inactif',
                                    'pending'  => 'en attente',
                                    'rejected' => 'rejeté',
                                    default    => $s ?? 'inconnu',
                                };
                            };

                            $label = match($log->event) {
                                'user_registration_requested' => 'Demande de création de compte',
                                'user_account_approved'       => 'Compte validé',
                                'user_account_rejected'       => 'Compte rejeté',
                                'created'                     => 'Création du compte',
                                'updated'                     => 'Mise à jour du compte',
                                default                       => ucfirst(str_replace('_', ' ', $log->event)),
                            };
                        @endphp
                        <div class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="me-3">
                                <div class="fw-semibold">{{ $label }}</div>
                                <div class="small text-muted">
                                    @if($oldStatus || $newStatus)
                                        @if($oldStatus && $newStatus)
                                            Statut : {{ $formatStatus($oldStatus) }} → {{ $formatStatus($newStatus) }}
                                        @elseif($newStatus)
                                            Nouveau statut : {{ $formatStatus($newStatus) }}
                                        @else
                                            Ancien statut : {{ $formatStatus($oldStatus) }}
                                        @endif
                                    @else
                                        Détail indisponible.
                                    @endif
                                </div>
                                @if($log->user)
                                    <div class="small text-muted">
                                        Par : {{ $log->user->name }} ({{ $log->user->email }})
                                    </div>
                                @endif
                            </div>
                            <div class="text-end small text-muted">
                                {{ $log->created_at?->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

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

@can('toggleActive', $user)
    {{-- Modal de rejet avec saisie du motif --}}
    <div class="modal fade" id="rejectUserModal" tabindex="-1" aria-labelledby="rejectUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectUserModalLabel">
                        <i class="bi bi-x-circle me-1 text-danger"></i>
                        Rejeter le compte utilisateur
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <form method="POST" action="{{ route('users.reject', $user) }}">
                    @csrf
                    <div class="modal-body">
                        <p class="mb-3">
                            Vous êtes sur le point de rejeter la demande de création de compte de
                            <strong>{{ $user->name }}</strong> ({{ $user->email }}).
                        </p>
                        <div class="mb-3">
                            <label for="reject-reason" class="form-label">Motif du rejet (optionnel)</label>
                            <textarea
                                id="reject-reason"
                                name="reason"
                                class="form-control"
                                rows="4"
                                placeholder="Exemple : Informations incomplètes, compte déjà existant, etc."
                            ></textarea>
                            <div class="form-text">
                                Ce motif pourra être communiqué à l'utilisateur dans l'e-mail de notification.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Annuler
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-x-circle me-1"></i>
                            Confirmer le rejet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endcan

@endsection
