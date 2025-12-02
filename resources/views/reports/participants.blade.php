@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Statistiques sur les participants</h4>
        <p class="text-muted mb-0">
            Taux de participation global et par service.
        </p>
    </div>
    <div class="btn-group">
        <a href="{{ route('reports.export', ['participants', 'pdf']) }}?start_date={{ $startDate->format('Y-m-d') }}&end_date={{ $endDate->format('Y-m-d') }}" 
           class="btn btn-outline-danger" target="_blank">
            <i class="bi bi-file-pdf"></i> Export PDF
        </a>
        <a href="{{ route('reports.export', ['participants', 'excel']) }}?start_date={{ $startDate->format('Y-m-d') }}&end_date={{ $endDate->format('Y-m-d') }}" 
           class="btn btn-outline-success">
            <i class="bi bi-file-excel"></i> Export Excel
        </a>
        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
            Retour aux rapports
        </a>
    </div>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('reports.participants') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small">Date de début</label>
                <input type="date" name="start_date" class="form-control" 
                       value="{{ $startDate->format('Y-m-d') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label small">Date de fin</label>
                <input type="date" name="end_date" class="form-control" 
                       value="{{ $endDate->format('Y-m-d') }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search me-1"></i> Filtrer
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 text-center">
            <div class="card-body">
                <h2 class="text-primary mb-2">{{ number_format($globalParticipationRate, 1) }}%</h2>
                <p class="text-muted mb-0">Taux de participation global</p>
                <small class="text-muted">{{ $totalConfirmed }} / {{ $totalInvitations }} confirmations</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 text-center">
            <div class="card-body">
                <h2 class="text-success mb-2">{{ number_format($attendanceRate, 1) }}%</h2>
                <p class="text-muted mb-0">Taux de présence</p>
                <small class="text-muted">{{ $totalAttended }} / {{ $totalInvitations }} présences</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 text-center">
            <div class="card-body">
                <h2 class="text-info mb-2">{{ $totalInvitations }}</h2>
                <p class="text-muted mb-0">Total d'invitations</p>
                <small class="text-muted">Sur la période sélectionnée</small>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white">
        <h6 class="mb-0">Statistiques par service</h6>
    </div>
    <div class="card-body">
        @if($byService->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th class="text-end">Invitations</th>
                            <th class="text-end">Confirmées</th>
                            <th class="text-end">Présences</th>
                            <th class="text-end">Taux de participation</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($byService as $item)
                            <tr>
                                <td><strong>{{ $item->service }}</strong></td>
                                <td class="text-end">{{ $item->total_invitations }}</td>
                                <td class="text-end">{{ $item->confirmed }}</td>
                                <td class="text-end">{{ $item->attended }}</td>
                                <td class="text-end">
                                    @if($item->total_invitations > 0)
                                        <span class="badge bg-{{ ($item->confirmed / $item->total_invitations * 100) >= 70 ? 'success' : (($item->confirmed / $item->total_invitations * 100) >= 50 ? 'warning' : 'danger') }}">
                                            {{ number_format($item->confirmed / $item->total_invitations * 100, 1) }}%
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted text-center mb-0">Aucune donnée disponible</p>
        @endif
    </div>
</div>
@endsection

