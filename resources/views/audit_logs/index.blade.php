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
