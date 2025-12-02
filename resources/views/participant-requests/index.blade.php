@extends('layouts.app')

@section('title', 'Demandes d\'ajout de participants')

@section('content')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
        <li class="breadcrumb-item active">Demandes de participants</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold">Demandes d'ajout de participants</h3>
        <p class="text-muted mb-0 small">Accueil / Demandes de participants</p>
    </div>
    @can('create', App\Models\ParticipantRequest::class)
    <a href="{{ route('participant-requests.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Nouvelle demande
    </a>
    @endcan
</div>

@include('partials.alerts')

{{-- Liste des demandes --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Participant</th>
                        <th>Réunion</th>
                        <th>Demandeur</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $request)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $request->participant_name }}</div>
                                @if($request->participant_email)
                                    <small class="text-muted">{{ $request->participant_email }}</small>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('meetings.show', $request->meeting) }}">
                                    {{ $request->meeting->title }}
                                </a>
                            </td>
                            <td>{{ $request->requester->name }}</td>
                            <td>
                                @php
                                    $statusClass = match($request->status) {
                                        'pending' => 'bg-warning text-dark',
                                        'approved' => 'bg-success',
                                        'rejected' => 'bg-danger',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ ucfirst($request->status) }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('participant-requests.show', $request) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                Aucune demande trouvée.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($requests->hasPages())
        <div class="modern-card-footer">
            <div class="small text-muted">
                Affichage de {{ $requests->firstItem() }} à {{ $requests->lastItem() }} 
                sur {{ $requests->total() }} demande{{ $requests->total() > 1 ? 's' : '' }}
            </div>
            <div class="pagination-modern">
                {{ $requests->appends(request()->query())->links() }}
            </div>
        </div>
    @endif
</div>
@endsection

