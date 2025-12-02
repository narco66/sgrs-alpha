@extends('layouts.app')

@section('content')
@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;

    $today = Carbon::today();
    $currentMonthLabel = $baseDate->locale('fr_FR')->translatedFormat('F Y');
    $prevDate = $baseDate->copy()->subMonthNoOverflow();
    $nextDate = $baseDate->copy()->addMonthNoOverflow();

    $startGrid = $baseDate->copy()->startOfMonth()->startOfWeek(Carbon::MONDAY);
    $endGrid = $baseDate->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);

    $meetingsByDate = $meetings->groupBy(fn($meeting) => Carbon::parse($meeting->start_at)->toDateString());
@endphp

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-1 fw-semibold">Calendrier Mensuel</h3>
        <div class="small">
            <a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Accueil</a>
            <span class="text-muted">/</span>
            <a href="{{ route('calendar.index') }}" class="text-decoration-none text-muted">Calendrier</a>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('calendar.day', ['date' => $baseDate->toDateString()]) }}" class="btn btn-outline-primary btn-sm">Jour</a>
        <a href="{{ route('calendar.week', ['date' => $baseDate->toDateString()]) }}" class="btn btn-outline-primary btn-sm">Semaine</a>
        <a href="{{ route('calendar.month', ['date' => $baseDate->toDateString()]) }}" class="btn btn-primary btn-sm">Mois</a>
        <a href="{{ route('calendar.year', ['year' => $baseDate->year]) }}" class="btn btn-outline-primary btn-sm">Année</a>
    </div>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('calendar.month', ['date' => $prevDate->toDateString()]) }}" class="btn btn-light border">
                <i class="bi bi-chevron-left"></i>
            </a>
            <span class="fw-semibold fs-6">{{ Str::ucfirst($currentMonthLabel) }}</span>
            <a href="{{ route('calendar.month', ['date' => $nextDate->toDateString()]) }}" class="btn btn-light border">
                <i class="bi bi-chevron-right"></i>
            </a>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('calendar.month', ['date' => $today->toDateString()]) }}" class="btn btn-outline-primary btn-sm">Aujourd'hui</a>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="calendar-month-grid">
            <div class="calendar-month-header bg-light">
                @foreach(['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'] as $dayLabel)
                    <div class="calendar-month-col-header">{{ $dayLabel }}</div>
                @endforeach
            </div>
            <div class="calendar-month-body">
                @for($date = $startGrid->copy(); $date->lte($endGrid); $date->addDay())
                    @php
                        $isCurrentMonth = $date->isSameMonth($baseDate);
                        $isToday = $date->isSameDay($today);
                        $dayMeetings = $meetingsByDate[$date->toDateString()] ?? collect();
                    @endphp
                    <div class="calendar-month-cell {{ $isCurrentMonth ? '' : 'calendar-month-cell-muted' }} {{ $isToday ? 'calendar-month-cell-today' : '' }}">
                        <div class="d-flex justify-content-between align-items-start">
                            <span class="fw-semibold">{{ $date->day }}</span>
                        </div>
                        <div class="calendar-month-events">
                            @foreach($dayMeetings as $meeting)
                                @php
                                    $start = Carbon::parse($meeting->start_at);
                                    $end = $meeting->end_at ? Carbon::parse($meeting->end_at) : null;
                                    $label = Str::limit($meeting->title, 20);
                                    $color = match($meeting->status) {
                                        'planifiee' => 'primary',
                                        'en_cours' => 'warning',
                                        'terminee' => 'success',
                                        'annulee' => 'danger',
                                        default => 'info',
                                    };
                                @endphp
                                <a href="{{ route('meetings.show', $meeting) }}" class="badge bg-{{ $color }} text-wrap text-start calendar-month-badge">
                                    <span class="small fw-semibold">{{ $start->format('d/m') }}</span>
                                    <span class="small ms-1">{{ $label }}</span>
                                    @if($end && !$start->isSameDay($end))
                                        <span class="badge bg-light text-dark border ms-1">→ {{ $end->format('d/m') }}</span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endfor
            </div>
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
                    <div class="text-muted small">Prochaines réunions du mois sélectionné</div>
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
                        <div class="badge bg-{{ $color }} me-3 px-3 py-2">{{ $start->format('d M') }}</div>
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
                    <p class="text-muted mb-0">Aucune réunion planifiée sur cette période.</p>
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
                    <li class="mb-2"><i class="bi bi-info-circle me-1 text-primary"></i>Utilisez les badges pour repérer les statuts.</li>
                    <li class="mb-2"><i class="bi bi-bell me-1 text-warning"></i>Pensez à confirmer ou annuler les invitations en attente.</li>
                    <li><i class="bi bi-clipboard-check me-1 text-success"></i>Vérifiez les salles et comités assignés avant envoi.</li>
                </ul>
                <textarea class="form-control" rows="4" placeholder="Ajoutez ici vos notes internes..."></textarea>
                <small class="text-muted d-block mt-2">Ces notes sont locales à cette page.</small>
            </div>
        </div>
    </div>
</div>

<style>
    .calendar-month-grid { display: grid; grid-template-rows: auto 1fr; width: 100%; }
    .calendar-month-header { display: grid; grid-template-columns: repeat(7, 1fr); border-bottom: 1px solid #e9ecef; }
    .calendar-month-col-header { padding: 0.85rem; font-weight: 600; text-align: center; color: #334155; }
    .calendar-month-body { display: grid; grid-template-columns: repeat(7, 1fr); grid-auto-rows: 130px; min-height: 520px; }
    .calendar-month-cell { border: 1px solid #eef1f4; padding: 0.75rem; background: #fff; display: flex; flex-direction: column; gap: 0.35rem; }
    .calendar-month-cell-muted { background: #f8fafc; color: #94a3b8; }
    .calendar-month-cell-today { box-shadow: inset 0 0 0 2px #2b6cb0; }
    .calendar-month-events { display: flex; flex-direction: column; gap: 0.25rem; }
    .calendar-month-badge { white-space: normal; line-height: 1.2; }
    @media (max-width: 991px) { .calendar-month-body { grid-auto-rows: 110px; } .calendar-month-col-header { font-size: 0.9rem; } }
</style>
@endsection
