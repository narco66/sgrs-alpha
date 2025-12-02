@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Statistiques sur les réunions</h4>
        <p class="text-muted mb-0">
            Analyse des réunions par type, statut et période.
        </p>
    </div>
    <div class="btn-group">
        <a href="{{ route('reports.export', ['meetings', 'pdf']) }}?start_date={{ $startDate->format('Y-m-d') }}&end_date={{ $endDate->format('Y-m-d') }}" 
           class="btn btn-outline-danger" target="_blank">
            <i class="bi bi-file-pdf"></i> Export PDF
        </a>
        <a href="{{ route('reports.export', ['meetings', 'excel']) }}?start_date={{ $startDate->format('Y-m-d') }}&end_date={{ $endDate->format('Y-m-d') }}" 
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
        <form method="GET" action="{{ route('reports.meetings') }}" class="row g-2 align-items-end">
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

<div class="row g-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h6 class="mb-0">Réunions par type</h6>
            </div>
            <div class="card-body">
                @if($byType->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th class="text-end">Nombre</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($byType as $item)
                                    <tr>
                                        <td>{{ $item->type ?? 'Non spécifié' }}</td>
                                        <td class="text-end"><strong>{{ $item->total }}</strong></td>
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
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h6 class="mb-0">Réunions par statut</h6>
            </div>
            <div class="card-body">
                @if($byStatus->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Statut</th>
                                    <th class="text-end">Nombre</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($byStatus as $item)
                                    <tr>
                                        <td>
                                            <span class="badge bg-secondary">{{ $item->status }}</span>
                                        </td>
                                        <td class="text-end"><strong>{{ $item->total }}</strong></td>
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
    </div>
</div>

@if($avgConvocationDelay && $avgConvocationDelay->avg_delay)
<div class="card shadow-sm border-0 mt-4">
    <div class="card-body">
        <h6 class="mb-3">Indicateurs de performance</h6>
        <div class="row">
            <div class="col-md-6">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-clock-history fs-2 text-primary"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="mb-0">{{ number_format($avgConvocationDelay->avg_delay, 1) }} jours</h5>
                        <small class="text-muted">Délai moyen de convocation</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

