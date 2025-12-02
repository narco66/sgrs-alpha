@extends('layouts.app')

@section('content')
@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;

    $today = Carbon::today();
    $prevDate = $baseDate->copy()->subDay();
    $nextDate = $baseDate->copy()->addDay();
    $label = $baseDate->locale('fr_FR')->translatedFormat('l j F Y');
    $meetingsByHour = $meetings->groupBy(fn($m) => Carbon::parse($m->start_at)->format('H'));
@endphp

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-1 fw-semibold">Calendrier Journalier</h3>
        <div class="small">
            <a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Accueil</a>
            <span class="text-muted">/</span>
            <a href="{{ route('calendar.index') }}" class="text-decoration-none text-muted">Calendrier</a>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('calendar.day', ['date' => $baseDate->toDateString()]) }}" class="btn btn-primary btn-sm">Jour</a>
        <a href="{{ route('calendar.week', ['date' => $baseDate->toDateString()]) }}" class="btn btn-outline-primary btn-sm">Semaine</a>
        <a href="{{ route('calendar.month', ['date' => $baseDate->toDateString()]) }}" class="btn btn-outline-primary btn-sm">Mois</a>
        <a href="{{ route('calendar.year', ['year' => $baseDate->year]) }}" class="btn btn-outline-primary btn-sm">Année</a>
    </div>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('calendar.day', ['date' => $prevDate->toDateString()]) }}" class="btn btn-light border">
                <i class="bi bi-chevron-left"></i>
            </a>
            <span class="fw-semibold fs-6">{{ Str::ucfirst($label) }}</span>
            <a href="{{ route('calendar.day', ['date' => $nextDate->toDateString()]) }}" class="btn btn-light border">
                <i class="bi bi-chevron-right"></i>
            </a>
        </div>
        <a href="{{ route('calendar.day', ['date' => $today->toDateString()]) }}" class="btn btn-outline-primary btn-sm">Aujourd'hui</a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="calendar-day-rail">
            @for($hour = 0; $hour < 24; $hour++)
                @php
                    $hourKey = str_pad($hour, 2, '0', STR_PAD_LEFT);
                    $items = $meetingsByHour[$hourKey] ?? collect();
                    $isCurrent = $today->isSameDay($baseDate) && $hour === now()->hour;
                @endphp
                <div class="calendar-day-row {{ $isCurrent ? 'calendar-day-row-current' : '' }}">
                    <div class="calendar-day-time">{{ $hourKey }}h</div>
                    <div class="calendar-day-slot">
                        @forelse($items as $meeting)
                            @php
                                $start = Carbon::parse($meeting->start_at);
                                $end = $meeting->end_at ? Carbon::parse($meeting->end_at) : null;
                                $color = match($meeting->status) {
                                    'planifiee' => 'primary',
                                    'en_cours' => 'warning',
                                    'terminee' => 'success',
                                    'annulee' => 'danger',
                                    default => 'info',
                                };
                            @endphp
                            <a href="{{ route('meetings.show', $meeting) }}" class="calendar-day-meeting bg-{{ $color }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold">{{ $start->format('H:i') }} @if($end)- {{ $end->format('H:i') }}@endif</span>
                                    <span class="badge bg-light text-dark border ms-2">{{ Str::limit($meeting->status ?? 'Statut', 12) }}</span>
                                </div>
                                <div class="small">{{ Str::limit($meeting->title, 60) }}</div>
                                @if($meeting->committee?->name || $meeting->room?->name)
                                    <div class="text-muted small mt-1 d-flex gap-2 align-items-center">
                                        @if($meeting->committee?->name)
                                            <span><i class="bi bi-people-fill me-1"></i>{{ Str::limit($meeting->committee->name, 30) }}</span>
                                        @endif
                                        @if($meeting->room?->name)
                                            <span><i class="bi bi-geo-alt me-1"></i>{{ Str::limit($meeting->room->name, 24) }}</span>
                                        @endif
                                    </div>
                                @endif
                            </a>
                        @empty
                            <div class="calendar-day-empty"></div>
                        @endforelse
                    </div>
                </div>
            @endfor
        </div>
    </div>
</div>

@php
    $upcoming = $meetings->sortBy('start_at')->take(5);
@endphp

<div class="row g-3 mt-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-semibold">À venir</div>
                    <div class="text-muted small">Prochaines réunions de la journée</div>
                </div>
                <a href="{{ route('meetings.index') }}" class="btn btn-sm btn-outline-primary">Voir toutes</a>
            </div>
            <div class="card-body">
                @forelse($upcoming as $meeting)
                    @php
                        $start = Carbon::parse($meeting->start_at);
                        $end = $meeting->end_at ? Carbon::parse($meeting->end_at) : null;
                        $color = match($meeting->status) {
                            'planifiee' => 'primary',
                            'en_cours' => 'warning',
                            'terminee' => 'success',
                            'annulee' => 'danger',
                            default => 'info',
                        };
                    @endphp
                    <div class="d-flex align-items-start mb-3 pb-3 border-bottom">
                        <div class="badge bg-{{ $color }} me-3 px-3 py-2">{{ $start->format('H:i') }}</div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ $meeting->title }}</div>
                            <div class="text-muted small">{{ $start->format('H:i') }} @if($end)- {{ $end->format('H:i') }}@endif • {{ $meeting->committee->name ?? 'Comité' }}</div>
                            @if($meeting->room?->name)
                                <div class="text-muted small"><i class="bi bi-geo-alt me-1"></i>{{ $meeting->room->name }}</div>
                            @endif
                        </div>
                        <a href="{{ route('meetings.show', $meeting) }}" class="btn btn-sm btn-outline-secondary">Détails</a>
                    </div>
                @empty
                    <p class="text-muted mb-0">Aucune réunion prévue pour cette journée.</p>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white">
                <div class="fw-semibold">Notes</div>
                <div class="text-muted small">Contexte ou actions rapides</div>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-3 small text-muted">
                    <li class="mb-2"><i class="bi bi-info-circle me-1 text-primary"></i>Ajoutez des rappels internes pour la journée.</li>
                    <li class="mb-2"><i class="bi bi-bell me-1 text-warning"></i>Confirmez les participants clés avant le début.</li>
                    <li><i class="bi bi-clipboard-check me-1 text-success"></i>Vérifiez la salle et l’équipement avant la première réunion.</li>
                </ul>
                <textarea class="form-control" rows="4" placeholder="Ajoutez ici vos notes internes..."></textarea>
                <small class="text-muted d-block mt-2">Ces notes sont locales à cette page.</small>
            </div>
        </div>
    </div>
</div>

<style>
    .calendar-day-rail { display: flex; flex-direction: column; }
    .calendar-day-row { display: grid; grid-template-columns: 70px 1fr; border-bottom: 1px solid #eef1f4; min-height: 70px; }
    .calendar-day-row:last-child { border-bottom: none; }
    .calendar-day-row-current { background: #f8fafc; border-left: 3px solid #2b6cb0; }
    .calendar-day-time { padding: 1rem; font-weight: 600; color: #64748b; background: #f8fafc; }
    .calendar-day-slot { padding: 0.75rem 1rem; display: flex; flex-direction: column; gap: 0.5rem; }
    .calendar-day-meeting { color: #fff; padding: 0.75rem 0.9rem; border-radius: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.08); text-decoration: none; }
    .calendar-day-empty { height: 24px; border: 1px dashed #e2e8f0; border-radius: 8px; background: #f8fafc; }
    .calendar-day-row .badge { font-size: 0.72rem; }
    @media (max-width: 768px) { .calendar-day-row { grid-template-columns: 55px 1fr; } .calendar-day-time { padding: 0.75rem; } }
</style>
@endsection
