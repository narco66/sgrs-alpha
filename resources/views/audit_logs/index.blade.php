@extends('layouts.app')

@section('title', 'Journal d\'audit')

@section('content')
<div class="container-fluid">
    {{-- En-tête --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="page-title mb-1">Journal d'audit</h3>
            <p class="text-muted mb-0 small">
                Traçabilité des actions utilisateurs et des événements du système SGRS-CEEAC.
            </p>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="filter-card mb-3">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Type d'événement</label>
                <select name="event" class="form-select">
                    <option value="">Tous</option>
                    @foreach($events as $eventValue)
                        <option value="{{ $eventValue }}" @selected(request('event') === $eventValue)>
                            {{ $eventValue }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Utilisateur (ID)</label>
                <input type="number" name="user_id" value="{{ request('user_id') }}" class="form-control" placeholder="ID utilisateur">
            </div>
            <div class="col-md-4">
                <label class="form-label">Modèle concerné</label>
                <input type="text" name="model" value="{{ request('model') }}" class="form-control" placeholder="Ex : App\Models\Meeting">
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-funnel me-1"></i> Filtrer
                </button>
            </div>
        </form>
    </div>

    {{-- Tableau des logs --}}
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Entrées du journal</h5>
                <span class="small text-muted">
                    Total : {{ $logs->total() }} action(s)
                </span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Événement</th>
                            <th>Utilisateur</th>
                            <th>Modèle</th>
                            <th>ID cible</th>
                            <th>IP</th>
                            <th>Plus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td class="small">
                                    {{ $log->created_at?->format('d/m/Y H:i') }}
                                </td>
                                <td><span class="badge bg-primary">{{ $log->event }}</span></td>
                                <td class="small">
                                    @if($log->user)
                                        #{{ $log->user_id }} – {{ $log->user->name }}
                                    @else
                                        <span class="text-muted">Anonyme / Système</span>
                                    @endif
                                </td>
                                <td class="small text-break">
                                    {{ $log->auditable_type ?? '-' }}
                                </td>
                                <td class="small">
                                    {{ $log->auditable_id ?? '-' }}
                                </td>
                                <td class="small">
                                    {{ $log->ip_address ?? '-' }}
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-secondary"
                                            type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#log-details-{{ $log->id }}">
                                        Détails
                                    </button>
                                </td>
                            </tr>
                            <tr class="collapse bg-light" id="log-details-{{ $log->id }}">
                                <td colspan="7">
                                    <div class="p-3 small">
                                        <div class="mb-2">
                                            <strong>User Agent :</strong>
                                            <span class="text-muted">{{ $log->user_agent ?? '-' }}</span>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <h6 class="fw-semibold small mb-1">Anciennes valeurs</h6>
                                                <pre class="small bg-white border rounded p-2 mb-0" style="max-height: 200px; overflow:auto;">{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="fw-semibold small mb-1">Nouvelles valeurs</h6>
                                                <pre class="small bg-white border rounded p-2 mb-0" style="max-height: 200px; overflow:auto;">{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                            </div>
                                        </div>
                                        @if(!empty($log->meta))
                                            <div class="mt-3">
                                                <h6 class="fw-semibold small mb-1">Meta</h6>
                                                <pre class="small bg-white border rounded p-2 mb-0" style="max-height: 200px; overflow:auto;">{{ json_encode($log->meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    Aucune entrée d'audit trouvée pour les filtres actuels.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($logs->hasPages())
            <div class="card-footer d-flex justify-content-between align-items-center">
                <div class="small text-muted">
                    Affichage de {{ $logs->firstItem() }} à {{ $logs->lastItem() }}
                    sur {{ $logs->total() }} entrée(s).
                </div>
                <div>
                    {{ $logs->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Journal des actions</h4>
        <div class="small">
            <a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Accueil</a>
            <span class="text-muted">/</span>
            <span class="text-muted">Journal des actions</span>
        </div>
        <p class="text-muted mb-0 mt-1">Suivi des opérations effectuées sur les réunions et autres objets audités.</p>
    </div>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('audit-logs.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small">Événement</label>
                <select name="event" class="form-select">
                    <option value="">Tous</option>
                    @foreach($events as $eventOption)
                        <option value="{{ $eventOption }}" @selected($filters['event'] === $eventOption)>
                            {{ $eventOption }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small">Utilisateur (ID)</label>
                <input type="number" name="user_id" value="{{ $filters['user_id'] }}" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label small">Modèle (classe)</label>
                <input type="text" name="model" value="{{ $filters['model'] }}"
                       class="form-control"
                       placeholder="App\Models\Meeting">
            </div>
            <div class="col-md-2 text-end">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="bi bi-funnel me-1"></i> Filtrer
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 align-middle">
                <thead class="table-light">
                    <tr class="small text-muted">
                        <th>Date / heure</th>
                        <th>Événement</th>
                        <th>Objet</th>
                        <th>Utilisateur</th>
                        <th>Anciennes valeurs</th>
                        <th>Nouvelles valeurs</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($logs as $log)
                    <tr class="small">
                        <td>
                            {{ $log->created_at?->format('d/m/Y H:i:s') }}
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $log->event }}
                            </span>
                        </td>
                        <td>
                            <div class="fw-semibold">
                                {{ class_basename($log->auditable_type) }} #{{ $log->auditable_id }}
                            </div>
                            <div class="text-muted">
                                {{ $log->auditable_type }}
                            </div>
                        </td>
                        <td>
                            @if($log->user)
                                <div class="fw-semibold">
                                    {{ $log->user->name }}
                                </div>
                                <div class="text-muted">
                                    ID {{ $log->user->id }}
                                </div>
                            @else
                                <span class="text-muted fst-italic">Système</span>
                            @endif
                        </td>
                        <td style="max-width: 220px;">
                            @if($log->old_values)
                                <pre class="mb-0 small text-muted"
                                     style="white-space: pre-wrap; word-break: break-word;">
{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td style="max-width: 220px;">
                            @if($log->new_values)
                                <pre class="mb-0 small text-muted"
                                     style="white-space: pre-wrap; word-break: break-word;">
{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Aucun événement d’audit enregistré.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="modern-card-footer">
                <div class="small text-muted">
                    Affichage de {{ $logs->firstItem() }} à {{ $logs->lastItem() }} 
                    sur {{ $logs->total() }} action{{ $logs->total() > 1 ? 's' : '' }}
                </div>
                <div class="pagination-modern">
                    {{ $logs->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
