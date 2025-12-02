@extends('layouts.app')

@section('title', 'Calendrier des réunions')

@section('content')
@php
    use Carbon\Carbon;
    use App\Enums\MeetingStatus;

    // Sécurisation minimale des types de vue
    $currentView = in_array($view, ['day', 'month', 'year']) ? $view : 'month';

    // Libellés pratiques
    $formattedDate = match($currentView) {
        'day'   => $baseDate->locale('fr_FR')->translatedFormat('l d F Y'),
        'month' => $baseDate->locale('fr_FR')->translatedFormat('F Y'),
        'year'  => $baseDate->year,
        default => $baseDate->locale('fr_FR')->translatedFormat('F Y'),
    };

    // Dates de navigation (précédent / suivant)
    $prevDate = match($currentView) {
        'day'   => $baseDate->copy()->subDay(),
        'month' => $baseDate->copy()->subMonthNoOverflow(),
        'year'  => $baseDate->copy()->subYear(),
        default => $baseDate->copy()->subMonthNoOverflow(),
    };

    $nextDate = match($currentView) {
        'day'   => $baseDate->copy()->addDay(),
        'month' => $baseDate->copy()->addMonthNoOverflow(),
        'year'  => $baseDate->copy()->addYear(),
        default => $baseDate->copy()->addMonthNoOverflow(),
    };

    // Regroupement des réunions par jour
    $meetingsByDate = $meetings->groupBy(function ($meeting) {
        return Carbon::parse($meeting->start_at)->toDateString();
    });

    // Regroupement par mois (pour la vue année)
    $meetingsByMonth = $meetings->groupBy(function ($meeting) {
        return Carbon::parse($meeting->start_at)->format('Y-m');
    });

    // Helper pour badge de statut
    function meetingStatusBadgeClass(?string $status): string {
        return match($status) {
            'brouillon'      => 'bg-secondary',
            'planifiee'      => 'bg-primary',
            'en_preparation' => 'bg-info',
            'en_cours'       => 'bg-warning text-dark',
            'terminee'       => 'bg-success',
            'annulee'        => 'bg-danger',
            default          => 'bg-light text-dark',
        };
    }

    function meetingStatusLabel(?string $status): string {
        return match($status) {
            'brouillon'      => 'Brouillon',
            'planifiee'      => 'Planifiée',
            'en_preparation' => 'En préparation',
            'en_cours'       => 'En cours',
            'terminee'       => 'Clôturée',
            'annulee'        => 'Annulée',
            default          => ucfirst($status ?? 'N/A'),
        };
    }

    // Pour la vue mois : génération du calendrier en grille
    if ($currentView === 'month') {
        $firstDayOfMonth = $startDate->copy()->startOfMonth();
        $lastDayOfMonth = $endDate->copy()->endOfMonth();
        $startOfCalendar = $firstDayOfMonth->copy()->startOfWeek(Carbon::MONDAY);
        $endOfCalendar = $lastDayOfMonth->copy()->endOfWeek(Carbon::SUNDAY);
        $today = Carbon::today();
    }
@endphp

{{-- EN-TÊTE --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold">Calendrier des réunions statutaires</h3>
        <p class="text-muted mb-0">
            <i class="bi bi-calendar3 me-1"></i>
            Visualisation {{ $currentView === 'day' ? 'journalière' : ($currentView === 'month' ? 'mensuelle' : 'annuelle') }}
            des réunions de la Commission de la CEEAC
        </p>
    </div>
    @can('create', App\Models\Meeting::class)
    <a href="{{ route('meetings.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Nouvelle réunion
    </a>
    @endcan
</div>

{{-- BARRE DE CONTRÔLE --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            {{-- Sélecteur de vue --}}
            <div class="btn-group" role="group">
                <a href="{{ route('calendar.day', ['date' => $baseDate->toDateString()]) }}"
                   class="btn btn-sm {{ $currentView === 'day' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Jour
                </a>
                <a href="{{ route('calendar.week', ['date' => $baseDate->toDateString()]) }}"
                   class="btn btn-sm {{ $currentView === 'week' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Semaine
                </a>
                <a href="{{ route('calendar.month', ['date' => $baseDate->toDateString()]) }}"
                   class="btn btn-sm {{ $currentView === 'month' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Mois
                </a>
                <a href="{{ route('calendar.year', ['year' => $baseDate->year]) }}"
                   class="btn btn-sm {{ $currentView === 'year' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Année
                </a>
                <a href="{{ route('calendar.month', ['date' => now()->toDateString()]) }}"
                   class="btn btn-sm btn-primary ms-2">
                    Aujourd'hui
                </a>
            </div>

            {{-- Navigation temporelle --}}
            <div class="d-flex align-items-center gap-2">
                @php
                    $prevRoute = match($currentView) {
                        'day' => route('calendar.day', ['date' => $prevDate->toDateString()]),
                        'week' => route('calendar.week', ['date' => $prevDate->toDateString()]),
                        'month' => route('calendar.month', ['date' => $prevDate->toDateString()]),
                        'year' => route('calendar.year', ['year' => $prevDate->year]),
                        default => route('calendar.month', ['date' => $prevDate->toDateString()]),
                    };
                    $nextRoute = match($currentView) {
                        'day' => route('calendar.day', ['date' => $nextDate->toDateString()]),
                        'week' => route('calendar.week', ['date' => $nextDate->toDateString()]),
                        'month' => route('calendar.month', ['date' => $nextDate->toDateString()]),
                        'year' => route('calendar.year', ['year' => $nextDate->year]),
                        default => route('calendar.month', ['date' => $nextDate->toDateString()]),
                    };
                @endphp
                <a href="{{ $prevRoute }}"
                   class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-chevron-left"></i>
                </a>

                <div class="px-4 py-2 bg-primary-subtle rounded-3 border-0">
                    <h5 class="mb-0 fw-bold text-primary">{{ $formattedDate }}</h5>
                </div>

                <a href="{{ $nextRoute }}"
                   class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- CONTENU SELON LA VUE --}}
@if($currentView === 'day')
    {{-- ===================== VUE JOUR ===================== --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 pb-0">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-calendar-day text-primary me-2"></i>
                Réunions du {{ $baseDate->locale('fr_FR')->translatedFormat('l d F Y') }}
            </h5>
        </div>
        <div class="card-body">
            @php
                $meetingsDay = $meetings->sortBy('start_at');
            @endphp

            @if($meetingsDay->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-calendar-x fs-1 text-muted d-block mb-3"></i>
                    <p class="text-muted mb-0">Aucune réunion planifiée pour cette journée.</p>
                </div>
            @else
                <div class="timeline">
                    @foreach($meetingsDay as $meeting)
                        @php
                            $start = Carbon::parse($meeting->start_at);
                            $end   = $meeting->end_at ? Carbon::parse($meeting->end_at) : null;
                        @endphp
                        <div class="d-flex align-items-start mb-4">
                            <div class="me-4 text-center" style="width: 100px;">
                                <div class="fw-bold fs-5 text-primary">{{ $start->format('H:i') }}</div>
                                @if($end)
                                    <div class="small text-muted">{{ $end->format('H:i') }}</div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="flex-grow-1">
                                                <a href="{{ route('meetings.show', $meeting) }}"
                                                   class="h5 mb-1 text-decoration-none fw-bold">
                                                    {{ $meeting->title }}
                                                </a>
                                                <div class="small text-muted mt-1">
                                                    @if($meeting->type)
                                                        <span class="badge bg-info-subtle text-info me-1">
                                                            {{ $meeting->type->name }}
                                                        </span>
                                                    @endif
                                                    @if($meeting->committee)
                                                        <span class="me-1">
                                                            <i class="bi bi-people me-1"></i>{{ $meeting->committee->name }}
                                                        </span>
                                                    @endif
                                                    @if($meeting->room)
                                                        <span class="me-1">
                                                            <i class="bi bi-geo-alt me-1"></i>{{ $meeting->room->name }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge {{ meetingStatusBadgeClass($meeting->status) }} mb-2">
                                                    {{ meetingStatusLabel($meeting->status) }}
                                                </span>
                                            </div>
                                        </div>
                                        @if($meeting->description)
                                            <p class="text-muted small mb-0">
                                                {{ Str::limit($meeting->description, 200) }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

@elseif($currentView === 'month')
    {{-- ===================== VUE MOIS - CALENDRIER EN GRILLE ===================== --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-calendar-month text-primary me-2"></i>
                Réunions du mois de {{ $baseDate->locale('fr_FR')->translatedFormat('F Y') }}
            </h5>
        </div>
        <div class="card-body p-0">
            {{-- En-têtes des jours de la semaine --}}
            <div class="calendar-grid">
                <div class="calendar-header">
                    @foreach(['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'] as $dayName)
                        <div class="calendar-day-header">
                            <strong>{{ $dayName }}</strong>
                        </div>
                    @endforeach
                </div>

                {{-- Grille du calendrier --}}
                <div class="calendar-body">
                    @php
                        $current = $startOfCalendar->copy();
                        // Préparer les réunions avec leurs dates de début et fin
                        $meetingsWithDates = $meetings->map(function($meeting) {
                            $start = Carbon::parse($meeting->start_at);
                            $end = $meeting->end_at ? Carbon::parse($meeting->end_at) : $start->copy()->addHours(2);
                            $startDate = $start->toDateString();
                            $endDate = $end->toDateString();
                            
                            // Calculer le nombre de jours calendaires (différence entre les dates)
                            // Utiliser diffInDays avec false pour obtenir un nombre entier
                            $daysSpan = max(1, (int)($start->diffInDays($end, false)) + 1);
                            
                            return [
                                'meeting' => $meeting,
                                'start' => $start,
                                'end' => $end,
                                'startDate' => $startDate,
                                'endDate' => $endDate,
                                'daysSpan' => $daysSpan,
                            ];
                        });
                    @endphp
                    @while($current->lte($endOfCalendar))
                        @php
                            $dateKey = $current->toDateString();
                            $isCurrentMonth = $current->month === $baseDate->month;
                            $isToday = $current->isToday();
                            $isWeekend = $current->isWeekend();
                            
                            // Trouver les réunions qui commencent ce jour
                            $dayMeetings = $meetingsWithDates->filter(function($item) use ($dateKey) {
                                return $item['startDate'] === $dateKey;
                            })->sortBy(function($item) {
                                return $item['start']->format('H:i');
                            });
                            
                            // Trouver les réunions qui continuent ce jour (mais ne commencent pas)
                            $continuingMeetings = $meetingsWithDates->filter(function($item) use ($dateKey) {
                                return $item['startDate'] < $dateKey && $item['endDate'] >= $dateKey;
                            });
                        @endphp

                        <div class="calendar-day {{ !$isCurrentMonth ? 'calendar-day-other-month' : '' }} {{ $isToday ? 'calendar-day-today' : '' }} {{ $isWeekend ? 'calendar-day-weekend' : '' }}"
                             data-date="{{ $dateKey }}">
                            <div class="calendar-day-number-wrapper">
                                <span class="calendar-day-number {{ $isToday ? 'calendar-day-number-today' : '' }}">
                                    {{ $current->day }}
                                </span>
                                @if($isToday)
                                    <span class="calendar-today-indicator"></span>
                                @endif
                            </div>
                            
                            <div class="calendar-day-content">
                                @if($dayMeetings->isNotEmpty() || $continuingMeetings->isNotEmpty())
                                    {{-- Réunions qui commencent ce jour --}}
                                    @foreach($dayMeetings->take(4) as $item)
                                        @php
                                            $meeting = $item['meeting'];
                                            $start = $item['start'];
                                            $end = $item['end'];
                                            $statusClass = meetingStatusBadgeClass($meeting->status);
                                            $daysSpan = $item['daysSpan'];
                                            
                                            // Formater la durée de manière lisible
                                            $totalMinutes = (int)$start->diffInMinutes($end);
                                            $hours = (int)floor($totalMinutes / 60);
                                            $minutes = (int)($totalMinutes % 60);
                                            $durationText = '';
                                            
                                            // Afficher la durée seulement si > 1 jour ou > 2 heures
                                            if ($daysSpan > 1) {
                                                $durationText = (int)$daysSpan . 'j';
                                            } elseif ($hours >= 2) {
                                                $durationText = (int)$hours . 'h';
                                            }
                                        @endphp
                                        
                                        <a href="{{ route('meetings.show', $meeting) }}" 
                                           class="calendar-meeting-bar {{ $statusClass }}"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           data-meeting-id="{{ $meeting->id }}"
                                           title="{{ $meeting->title }} - {{ $start->format('d/m/Y H:i') }} @if($end && $end->ne($start)) au {{ $end->format('d/m/Y H:i') }} @endif">
                                            <div class="calendar-meeting-bar-content">
                                                <span class="calendar-meeting-time">{{ $start->format('H:i') }}</span>
                                                <span class="calendar-meeting-title">{{ Str::limit($meeting->title, 18) }}</span>
                                                @if($daysSpan > 1 || $hours >= 1)
                                                    <span class="calendar-meeting-duration-badge">{{ $durationText }}</span>
                                                @endif
                                            </div>
                                        </a>
                                    @endforeach
                                    
                                    {{-- Réunions qui continuent ce jour --}}
                                    @foreach($continuingMeetings->take(4 - $dayMeetings->count()) as $item)
                                        @php
                                            $meeting = $item['meeting'];
                                            $statusClass = meetingStatusBadgeClass($meeting->status);
                                        @endphp
                                        <a href="{{ route('meetings.show', $meeting) }}" 
                                           class="calendar-meeting-continuation {{ $statusClass }}"
                                           data-meeting-id="{{ $meeting->id }}"
                                           data-bs-toggle="tooltip"
                                           title="{{ $meeting->title }}">
                                        </a>
                                    @endforeach
                                    
                                    @if(($dayMeetings->count() + $continuingMeetings->count()) > 4)
                                        <a href="{{ route('calendar.index', ['view' => 'day', 'date' => $current->toDateString()]) }}" 
                                           class="calendar-meeting-more-btn text-decoration-none">
                                            <i class="bi bi-three-dots"></i>
                                            +{{ ($dayMeetings->count() + $continuingMeetings->count()) - 4 }} autre{{ (($dayMeetings->count() + $continuingMeetings->count()) - 4) > 1 ? 's' : '' }}
                                        </a>
                                    @endif
                                @else
                                    <div class="calendar-day-empty">
                                        <i class="bi bi-calendar-x"></i>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @php
                            $current->addDay();
                        @endphp
                    @endwhile
                </div>
            </div>
        </div>
    </div>

    {{-- LÉGENDE DES STATUTS --}}
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-white border-0">
            <h6 class="mb-0 fw-semibold">
                <i class="bi bi-palette me-2 text-primary"></i>Légende des statuts
            </h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-2 col-sm-4 col-6">
                    <div class="d-flex align-items-center p-2 rounded" style="background: rgba(102, 126, 234, 0.1);">
                        <div class="legend-color-bar bg-primary me-3"></div>
                        <span class="small fw-semibold">Planifiée</span>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6">
                    <div class="d-flex align-items-center p-2 rounded" style="background: rgba(79, 172, 254, 0.1);">
                        <div class="legend-color-bar bg-info me-3"></div>
                        <span class="small fw-semibold">En préparation</span>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6">
                    <div class="d-flex align-items-center p-2 rounded" style="background: rgba(240, 147, 251, 0.1);">
                        <div class="legend-color-bar bg-warning me-3"></div>
                        <span class="small fw-semibold">En cours</span>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6">
                    <div class="d-flex align-items-center p-2 rounded" style="background: rgba(67, 233, 123, 0.1);">
                        <div class="legend-color-bar bg-success me-3"></div>
                        <span class="small fw-semibold">Clôturée</span>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6">
                    <div class="d-flex align-items-center p-2 rounded" style="background: rgba(250, 112, 154, 0.1);">
                        <div class="legend-color-bar bg-danger me-3"></div>
                        <span class="small fw-semibold">Annulée</span>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6">
                    <div class="d-flex align-items-center p-2 rounded" style="background: rgba(134, 142, 150, 0.1);">
                        <div class="legend-color-bar bg-secondary me-3"></div>
                        <span class="small fw-semibold">Brouillon</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

@elseif($currentView === 'year')
    {{-- ===================== VUE ANNÉE ===================== --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-calendar3 text-primary me-2"></i>
                Réunions de l'année {{ $baseDate->year }}
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-4">
                @for($month = 1; $month <= 12; $month++)
                    @php
                        $monthDate = Carbon::create($baseDate->year, $month, 1);
                        $monthKey  = $monthDate->format('Y-m');
                        $monthMeetings = $meetingsByMonth->get($monthKey, collect());
                        $monthCount    = $monthMeetings->count();
                    @endphp

                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0 fw-bold">{{ $monthDate->locale('fr_FR')->translatedFormat('F') }}</h6>
                                    <span class="badge bg-primary">
                                        {{ $monthCount }} réunion{{ $monthCount > 1 ? 's' : '' }}
                                    </span>
                                </div>

                                @if($monthCount === 0)
                                    <div class="text-center py-3">
                                        <i class="bi bi-calendar-x text-muted d-block mb-2"></i>
                                        <p class="text-muted small mb-0">Aucune réunion prévue</p>
                                    </div>
                                @else
                                    <ul class="list-unstyled mb-0">
                                        @foreach($monthMeetings->sortBy('start_at')->take(5) as $meeting)
                                            @php
                                                $start = Carbon::parse($meeting->start_at);
                                            @endphp
                                            <li class="mb-2 pb-2 border-bottom">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div class="flex-grow-1">
                                                        <div class="small text-muted mb-1">
                                                            {{ $start->format('d/m') }} à {{ $start->format('H:i') }}
                                                        </div>
                                                        <a href="{{ route('meetings.show', $meeting) }}"
                                                           class="text-decoration-none fw-semibold small">
                                                            {{ Str::limit($meeting->title, 35) }}
                                                        </a>
                                                    </div>
                                                    <span class="badge {{ meetingStatusBadgeClass($meeting->status) }} ms-2" style="font-size: 0.7rem;">
                                                        {{ meetingStatusLabel($meeting->status) }}
                                                    </span>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                    @if($monthCount > 5)
                                        <div class="mt-3 text-center">
                                            <a href="{{ route('calendar.index', [
                                                        'view' => 'month',
                                                        'date' => $monthDate->toDateString()
                                                    ]) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                Voir toutes ({{ $monthCount }})
                                            </a>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
@endif

@push('styles')
<style>
    /* Calendrier en grille */
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 0;
        border: 2px solid #e9ecef;
        border-radius: 16px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08), 0 2px 8px rgba(0,0,0,0.04);
        transition: box-shadow 0.3s ease;
    }

    .calendar-grid:hover {
        box-shadow: 0 6px 24px rgba(0,0,0,0.12), 0 4px 12px rgba(0,0,0,0.06);
    }

    .calendar-header {
        display: contents;
    }

    .calendar-day-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 16px 10px;
        text-align: center;
        font-size: 0.9rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        border-bottom: 2px solid rgba(255,255,255,0.25);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        position: relative;
    }

    .calendar-day-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
    }

    .calendar-body {
        display: contents;
    }

    .calendar-day {
        min-height: 200px;
        border: 1px solid #e9ecef;
        padding: 12px 10px;
        background: #fff;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        display: flex;
        flex-direction: column;
        overflow: visible;
        cursor: pointer;
    }

    .calendar-day:hover {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        z-index: 2;
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.15);
        transform: translateY(-2px);
        border-color: rgba(102, 126, 234, 0.3);
    }

    .calendar-day:active {
        transform: translateY(0);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
    }

    .calendar-day-other-month {
        background: #f8f9fa;
        opacity: 0.5;
    }

    .calendar-day-other-month .calendar-day-number {
        color: #adb5bd;
    }

    .calendar-day-today {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.12) 0%, rgba(118, 75, 162, 0.12) 100%);
        border: 2px solid #667eea;
        z-index: 1;
        box-shadow: inset 0 0 20px rgba(102, 126, 234, 0.1);
    }

    .calendar-day-today .calendar-day-number {
        color: #667eea;
        font-weight: 700;
    }

    .calendar-day-weekend {
        background: #fafbfc;
    }

    .calendar-day-number-wrapper {
        position: relative;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .calendar-day-number {
        font-weight: 600;
        font-size: 1rem;
        color: #495057;
        position: relative;
    }

    .calendar-day-number-today {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% {
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        50% {
            box-shadow: 0 4px 16px rgba(102, 126, 234, 0.6);
        }
    }

    .calendar-today-indicator {
        position: absolute;
        top: 0;
        right: 0;
        width: 8px;
        height: 8px;
        background: #667eea;
        border-radius: 50%;
        border: 2px solid white;
        box-shadow: 0 0 0 2px #667eea;
    }

    .calendar-day-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 4px;
        overflow: hidden;
    }

    .calendar-day-empty {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 1;
        color: #adb5bd;
        font-size: 0.75rem;
        opacity: 0.4;
        transition: all 0.3s ease;
    }

    .calendar-day-empty i {
        font-size: 1.5rem;
    }

    .calendar-day:hover .calendar-day-empty {
        opacity: 0.6;
        transform: scale(1.1);
    }

    .calendar-meeting-bar {
        display: block;
        padding: 10px 12px;
        border-radius: 10px;
        text-decoration: none;
        color: white;
        font-size: 0.8rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0,0,0,0.25), 0 2px 4px rgba(0,0,0,0.1);
        border-left: 5px solid rgba(255,255,255,0.95);
        margin-bottom: 8px;
        position: relative;
        overflow: hidden;
        min-height: 42px;
        display: flex;
        align-items: center;
        width: 100%;
        font-weight: 600;
        backdrop-filter: blur(10px);
    }

    .calendar-meeting-bar::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 5px;
        background: rgba(255,255,255,0.95);
        border-radius: 10px 0 0 10px;
        box-shadow: 2px 0 4px rgba(0,0,0,0.1);
    }

    .calendar-meeting-bar::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .calendar-meeting-bar:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 8px 20px rgba(0,0,0,0.4), 0 4px 8px rgba(0,0,0,0.2);
        color: white;
        text-decoration: none;
        z-index: 15;
        border-left-width: 6px;
    }

    .calendar-meeting-bar:hover::after {
        opacity: 1;
    }

    .calendar-meeting-bar:active {
        transform: translateY(-1px) scale(1.01);
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }

    .calendar-meeting-bar-content {
        display: flex;
        align-items: center;
        gap: 8px;
        width: 100%;
        overflow: hidden;
        position: relative;
        z-index: 1;
    }

    .calendar-meeting-time {
        font-weight: 700;
        font-size: 0.75rem;
        opacity: 1;
        white-space: nowrap;
        flex-shrink: 0;
        background: rgba(0,0,0,0.35);
        padding: 5px 10px;
        border-radius: 6px;
        backdrop-filter: blur(8px);
        margin-right: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        letter-spacing: 0.5px;
    }

    .calendar-meeting-title {
        flex: 1;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-weight: 600;
        font-size: 0.82rem;
        line-height: 1.4;
        text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        letter-spacing: 0.3px;
    }

    .calendar-meeting-duration-badge {
        font-size: 0.7rem;
        background: rgba(0,0,0,0.3);
        padding: 4px 8px;
        border-radius: 5px;
        font-weight: 700;
        flex-shrink: 0;
        margin-left: auto;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        backdrop-filter: blur(4px);
        letter-spacing: 0.5px;
    }

    .calendar-meeting-continuation {
        display: block;
        height: 10px;
        border-radius: 5px;
        margin-bottom: 8px;
        opacity: 0.75;
        position: relative;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        box-shadow: 0 2px 6px rgba(0,0,0,0.25);
    }

    .calendar-meeting-continuation:hover {
        opacity: 1;
        height: 14px;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.35);
        z-index: 12;
    }

    .calendar-meeting-continuation::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: rgba(255,255,255,0.9);
        border-radius: 4px 0 0 4px;
    }

    .calendar-meeting-more-btn {
        width: 100%;
        padding: 6px 8px;
        border: 2px dashed #adb5bd;
        background: rgba(102, 126, 234, 0.05);
        color: #6c757d;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        margin-top: 4px;
        text-align: center;
    }

    .calendar-meeting-more-btn:hover {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        border-color: #667eea;
        border-style: solid;
        color: #667eea;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.2);
    }

    /* Couleurs spécifiques par statut - Barres principales */
    .calendar-meeting-bar.bg-primary,
    .calendar-meeting-continuation.bg-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .calendar-meeting-bar.bg-info,
    .calendar-meeting-continuation.bg-info {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .calendar-meeting-bar.bg-warning,
    .calendar-meeting-continuation.bg-warning {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .calendar-meeting-bar.bg-success,
    .calendar-meeting-continuation.bg-success {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }

    .calendar-meeting-bar.bg-danger,
    .calendar-meeting-continuation.bg-danger {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }

    .calendar-meeting-bar.bg-secondary,
    .calendar-meeting-continuation.bg-secondary {
        background: linear-gradient(135deg, #868e96 0%, #495057 100%);
    }

    /* Légende */
    .legend-color-bar {
        width: 40px;
        height: 12px;
        border-radius: 6px;
        flex-shrink: 0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .calendar-day {
            min-height: 140px;
            padding: 8px 6px;
        }
    }

    @media (max-width: 768px) {
        .calendar-day {
            min-height: 120px;
            padding: 6px 4px;
        }

        .calendar-day-header {
            padding: 10px 4px;
            font-size: 0.75rem;
        }

        .calendar-meeting-item {
            font-size: 0.65rem;
            padding: 4px 6px;
        }

        .calendar-meeting-time {
            font-size: 0.65rem;
        }

        .calendar-day-number {
            font-size: 0.85rem;
        }

        .calendar-day-number-today {
            width: 24px;
            height: 24px;
        }
    }

    @media (max-width: 576px) {
        .calendar-day {
            min-height: 80px;
        }

        .calendar-meeting-item {
            padding: 3px 4px;
            font-size: 0.6rem;
        }

        .calendar-day-content {
            gap: 2px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Initialiser les tooltips Bootstrap avec options améliorées
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                animation: true,
                delay: { show: 300, hide: 100 },
                html: true,
                placement: 'top'
            });
        });

        // Améliorer l'interactivité des jours du calendrier
        const calendarDays = document.querySelectorAll('.calendar-day');
        calendarDays.forEach(day => {
            day.addEventListener('click', function(e) {
                // Si on clique sur le jour (pas sur une réunion), naviguer vers la vue jour
                if (!e.target.closest('.calendar-meeting-bar') && 
                    !e.target.closest('.calendar-meeting-continuation') &&
                    !e.target.closest('.calendar-meeting-more-btn')) {
                    const date = this.getAttribute('data-date');
                    if (date) {
                        window.location.href = '{{ route("calendar.index", ["view" => "day"]) }}?date=' + date;
                    }
                }
            });
        });

        // Animation d'entrée pour les barres de réunion
        const meetingBars = document.querySelectorAll('.calendar-meeting-bar');
        meetingBars.forEach((bar, index) => {
            bar.style.opacity = '0';
            bar.style.transform = 'translateY(10px)';
            setTimeout(() => {
                bar.style.transition = 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
                bar.style.opacity = '1';
                bar.style.transform = 'translateY(0)';
            }, index * 50);
        });
    });
</script>
@endpush

@endsection
