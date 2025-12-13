@extends('layouts.app')

@section('title', 'Participants (toutes délégations)')

@section('content')
{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
        <li class="breadcrumb-item active">Participants</li>
    </ol>
</nav>

{{-- En-tete de page --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="page-title mb-1">Participants (toutes délégations)</h3>
        <p class="text-muted mb-0 small">Vue globale des personnes issues des délégations, par réunion.</p>
    </div>
    <a href="{{ route('meetings.index') }}" class="btn btn-modern btn-modern-secondary">
        <i class="bi bi-calendar3"></i>
        Gérer les participants par réunion
    </a>
</div>

@if(session('success'))
    <x-modern-alert type="success" dismissible>
        {{ session('success') }}
    </x-modern-alert>
@endif

{{-- Filtres --}}
<div class="modern-card mb-3">
    <div class="modern-card-header">
        <h5 class="modern-card-title">
            <i class="bi bi-funnel"></i>
            Filtres
        </h5>
    </div>
    <div class="modern-card-body">
        <form method="GET" action="{{ route('participants.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Recherche</label>
                <input type="text" name="q" class="form-control" value="{{ $search ?? '' }}" placeholder="Nom, email, institution, pays, délégation">
            </div>
            <div class="col-md-3">
                <label class="form-label">Réunion</label>
                <select name="meeting_id" class="form-select">
                    <option value="">Toutes</option>
                    @foreach($meetings ?? [] as $meeting)
                        <option value="{{ $meeting->id }}" @selected(($meetingId ?? '') == $meeting->id)>
                            {{ $meeting->title }} - {{ $meeting->start_at?->format('d/m') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Statut individuel</label>
                <select name="status" class="form-select">
                    <option value="all" @selected(($status ?? 'all') === 'all')>Tous</option>
                    <option value="invited" @selected(($status ?? '') === 'invited')>Invités</option>
                    <option value="confirmed" @selected(($status ?? '') === 'confirmed')>Confirmés</option>
                    <option value="present" @selected(($status ?? '') === 'present')>Présents</option>
                    <option value="absent" @selected(($status ?? '') === 'absent')>Absents</option>
                    <option value="excused" @selected(($status ?? '') === 'excused')>Excusés</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-modern btn-modern-primary w-100" title="Appliquer les filtres">
                    <i class="bi bi-search"></i>
                </button>
                <a href="{{ route('participants.index') }}" class="btn btn-modern btn-modern-secondary" title="Réinitialiser">
                    <i class="bi bi-arrow-repeat"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<div class="modern-card">
    <div class="modern-card-header">
        <h5 class="modern-card-title">
            <i class="bi bi-people"></i>
            Liste des participants (membres de délégation)
        </h5>
        <span class="badge-modern badge-modern-primary">
            {{ $participants->total() }} participant{{ $participants->total() > 1 ? 's' : '' }}
        </span>
    </div>
    <div class="modern-card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Participant</th>
                        <th>Email</th>
                        <th>Institution / Pays</th>
                        <th>Délégation</th>
                        <th>Réunion</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($participants as $participant)
                    @php
                        $fullName    = $participant->full_name;
                        $delegation  = $participant->delegation ?? null;
                        $meeting     = $delegation?->meeting ?? null;
                        $entityLabel = $delegation
                            ? ($delegation->organization_name
                                ?: $delegation->country
                                ?: $delegation->title)
                            : null;

                        $roleLabels = [
                            'head'       => 'Chef de délégation',
                            'member'     => 'Membre',
                            'expert'     => 'Expert',
                            'observer'   => 'Observateur',
                            'secretary'  => 'Secrétaire',
                            'advisor'    => 'Conseiller',
                            'interpreter'=> 'Interprète',
                        ];

                        $statusColors = [
                            'invited'   => 'warning',
                            'confirmed' => 'success',
                            'present'   => 'primary',
                            'absent'    => 'danger',
                            'excused'   => 'secondary',
                        ];

                        $statusLabels = [
                            'invited'   => 'Invité',
                            'confirmed' => 'Confirmé',
                            'present'   => 'Présent',
                            'absent'    => 'Absent',
                            'excused'   => 'Excusé',
                        ];
                    @endphp
                    <tr>
                        <td class="fw-semibold">
                            {{ $fullName ?: 'Participant' }}
                            @if($participant->role)
                                <div class="small text-muted">
                                    {{ $roleLabels[$participant->role] ?? ucfirst($participant->role) }}
                                </div>
                            @endif
                        </td>
                        <td>{{ $participant->email ?? 'N/A' }}</td>
                        <td>
                            @if($participant->institution)
                                <div>{{ $participant->institution }}</div>
                            @endif
                            @if($delegation?->country)
                                <div class="small text-muted">{{ $delegation->country }}</div>
                            @endif
                        </td>
                        <td>
                            @if($delegation)
                                <a href="{{ route('delegations.show', $delegation) }}" class="text-decoration-none">
                                    {{ $delegation->title ?? $entityLabel ?? 'Délégation' }}
                                </a>
                            @else
                                <span class="text-muted">Non rattaché</span>
                            @endif
                        </td>
                        <td>
                            @if($meeting)
                                <a href="{{ route('meetings.show', $meeting) }}" class="text-decoration-none">
                                    {{ $meeting->title }}
                                </a>
                                @if($meeting->start_at)
                                    <div class="small text-muted">{{ $meeting->start_at->format('d/m/Y') }}</div>
                                @endif
                            @else
                                <span class="text-muted">Aucune réunion</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $s = $participant->status ?? 'invited';
                            @endphp
                            <span class="badge bg-{{ $statusColors[$s] ?? 'secondary' }}">
                                {{ $statusLabels[$s] ?? ucfirst($s) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi bi-inbox empty-state-icon"></i>
                            <div class="empty-state-title">Aucun participant trouvé</div>
                            <div class="empty-state-text">Aucun membre de délégation ne correspond aux filtres actuels.</div>
                                <a href="{{ route('participants.index') }}" class="btn btn-modern btn-modern-secondary mt-3">Réinitialiser les filtres</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($participants->hasPages())
            <div class="modern-card-footer">
                <div class="small text-muted">
                    Affichage de {{ $participants->firstItem() }} à {{ $participants->lastItem() }}
                    sur {{ $participants->total() }} participant{{ $participants->total() > 1 ? 's' : '' }}
                </div>
                <div class="pagination-modern">
                    {{ $participants->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
