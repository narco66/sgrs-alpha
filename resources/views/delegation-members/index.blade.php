@extends('layouts.app')

@section('title', 'Membres - ' . $delegation->title)

@section('content')
<div class="container-fluid py-4">
    {{-- En-tête --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">
                <i class="bi bi-people text-primary"></i>
                Membres de la délégation
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('delegations.index') }}">Délégations</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('delegations.show', $delegation) }}">{{ $delegation->title }}</a></li>
                    <li class="breadcrumb-item active">Membres</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('delegations.show', $delegation) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
            @can('update', $delegation)
                <a href="{{ route('delegations.members.create', $delegation) }}" class="btn btn-primary">
                    <i class="bi bi-person-plus"></i> Ajouter un membre
                </a>
            @endcan
        </div>
    </div>

    {{-- Carte info délégation --}}
    <div class="card border-primary mb-4">
        <div class="card-body py-2">
            <div class="d-flex align-items-center">
                <i class="bi bi-people-fill text-primary me-2"></i>
                <span class="fw-semibold">{{ $delegation->title }}</span>
                @if($delegation->country)
                    <span class="badge bg-secondary ms-2">{{ $delegation->country }}</span>
                @endif
                @if($delegation->meeting)
                    <span class="text-muted ms-auto">
                        <i class="bi bi-calendar-event"></i> {{ $delegation->meeting->title }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- Liste des membres --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-list"></i> Liste des membres ({{ $members->count() }})
            </h5>
            @if($members->count() > 0)
                <a href="{{ route('delegations.badges.all', $delegation) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-printer"></i> Générer tous les badges
                </a>
            @endif
        </div>
        <div class="card-body p-0">
            @if($members->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nom complet</th>
                                <th>Fonction</th>
                                <th>Rôle</th>
                                <th>Contact</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($members as $member)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($member->photo_url)
                                                <img src="{{ $member->photo_url }}"
                                                     alt="{{ $member->full_name }}"
                                                     class="rounded-circle me-2"
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="avatar-sm bg-{{ $member->role === 'head' ? 'danger' : 'primary' }} bg-opacity-10 rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="bi bi-person text-{{ $member->role === 'head' ? 'danger' : 'primary' }}"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-semibold">
                                                    @if($member->title)
                                                        <span class="text-muted">{{ $member->title }}</span>
                                                    @endif
                                                    {{ $member->first_name }} {{ $member->last_name }}
                                                </div>
                                                @if($member->institution)
                                                    <small class="text-muted">{{ $member->institution }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $member->position ?? '-' }}</td>
                                    <td>
                                        @php
                                            $roleColors = [
                                                'head' => 'danger',
                                                'member' => 'primary',
                                                'expert' => 'info',
                                                'observer' => 'secondary',
                                                'secretary' => 'warning',
                                                'advisor' => 'success',
                                                'interpreter' => 'dark',
                                            ];
                                            $roleLabels = [
                                                'head' => 'Chef de délégation',
                                                'member' => 'Membre',
                                                'expert' => 'Expert',
                                                'observer' => 'Observateur',
                                                'secretary' => 'Secrétaire',
                                                'advisor' => 'Conseiller',
                                                'interpreter' => 'Interprète',
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $roleColors[$member->role] ?? 'secondary' }}">
                                            {{ $roleLabels[$member->role] ?? ucfirst($member->role) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($member->email)
                                            <a href="mailto:{{ $member->email }}" class="text-decoration-none">
                                                <i class="bi bi-envelope"></i> {{ $member->email }}
                                            </a>
                                        @endif
                                        @if($member->phone)
                                            <br><small class="text-muted"><i class="bi bi-telephone"></i> {{ $member->phone }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'invited' => 'warning',
                                                'confirmed' => 'success',
                                                'present' => 'primary',
                                                'absent' => 'danger',
                                                'excused' => 'secondary',
                                            ];
                                            $statusLabels = [
                                                'invited' => 'Invité',
                                                'confirmed' => 'Confirmé',
                                                'present' => 'Présent',
                                                'absent' => 'Absent',
                                                'excused' => 'Excusé',
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$member->status] ?? 'secondary' }}">
                                            {{ $statusLabels[$member->status] ?? ucfirst($member->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('delegations.members.badge', [$delegation, $member]) }}">
                                                        <i class="bi bi-printer text-primary"></i> Badge PDF
                                                    </a>
                                                </li>
                                                @can('update', $delegation)
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('delegations.members.edit', [$delegation, $member]) }}">
                                                            <i class="bi bi-pencil text-warning"></i> Modifier
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('delegations.members.destroy', [$delegation, $member]) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce membre ?')">
                                                                <i class="bi bi-trash"></i> Supprimer
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-people display-4 text-muted"></i>
                    <p class="text-muted mt-2 mb-3">Aucun membre dans cette délégation</p>
                    @can('update', $delegation)
                        <a href="{{ route('delegations.members.create', $delegation) }}" class="btn btn-primary">
                            <i class="bi bi-person-plus"></i> Ajouter le premier membre
                        </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>
@endsection












