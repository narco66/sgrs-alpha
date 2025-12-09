@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active">Tableau de bord</li>
    </ol>
</nav>

{{-- EN-TÊTE AVEC SALUTATION --}}
<div class="modern-card mb-4">
    <div class="modern-card-body">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h3 class="page-title mb-2">
                    <i class="bi bi-speedometer2 text-primary me-2"></i>
                    Bienvenue, {{ $user->name ?? 'Utilisateur' }}
                </h3>
                <p class="text-muted mb-0">
                    <i class="bi bi-calendar3 me-1"></i>
                    {{ now()->translatedFormat('l d F Y') }} • Tableau de bord SGRS-CEEAC
                </p>
            </div>
            <div class="text-end">
                <div class="badge-modern badge-modern-primary px-3 py-2">
                    <i class="bi bi-clock me-1"></i>
                    {{ now()->format('H:i') }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- KPI PRINCIPAUX - DESIGN MODERNE PROFESSIONNEL --}}
<div class="row g-4 mb-4">
    {{-- Réunions ce mois --}}
    <div class="col-xl-3 col-md-6">
        <div class="kpi-card-modern h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
            <div class="card-body text-white position-relative overflow-hidden">
                <div class="kpi-pattern"></div>
                <div class="d-flex justify-content-between align-items-start mb-3 position-relative">
                    <div>
                        <p class="mb-1 opacity-90 small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">Réunions ce mois</p>
                        <h2 class="mb-0 fw-bold" style="font-size: 2.5rem; line-height: 1.2;">{{ $meetingsThisMonth }}</h2>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 shadow-lg" style="backdrop-filter: blur(10px);">
                        <i class="bi bi-calendar-week" style="font-size: 2rem;"></i>
                    </div>
                </div>
                @if($meetingsGrowth != 0)
                <div class="d-flex align-items-center position-relative">
                    <i class="bi bi-arrow-{{ $meetingsGrowth > 0 ? 'up' : 'down' }}-right me-1 fs-5"></i>
                    <span class="small fw-semibold">{{ abs($meetingsGrowth) }}% vs mois dernier</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Taux de participation --}}
    <div class="col-xl-3 col-md-6">
        <div class="kpi-card-modern h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border: none;">
            <div class="card-body text-white position-relative overflow-hidden">
                <div class="kpi-pattern"></div>
                <div class="d-flex justify-content-between align-items-start mb-3 position-relative">
                    <div>
                        <p class="mb-1 opacity-90 small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">Taux de participation</p>
                        <h2 class="mb-0 fw-bold" style="font-size: 2.5rem; line-height: 1.2;">{{ $participationRate }}%</h2>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 shadow-lg" style="backdrop-filter: blur(10px);">
                        <i class="bi bi-people-fill" style="font-size: 2rem;"></i>
                    </div>
                </div>
                <div class="small opacity-90 fw-semibold position-relative">
                    {{ $confirmedInvitations ?? 0 }} confirmations
                </div>
            </div>
        </div>
    </div>

    {{-- Documents validés --}}
    <div class="col-xl-3 col-md-6">
        <div class="kpi-card-modern h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border: none;">
            <div class="card-body text-white position-relative overflow-hidden">
                <div class="kpi-pattern"></div>
                <div class="d-flex justify-content-between align-items-start mb-3 position-relative">
                    <div>
                        <p class="mb-1 opacity-90 small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">Documents validés</p>
                        <h2 class="mb-0 fw-bold" style="font-size: 2.5rem; line-height: 1.2;">{{ $approvedDocuments }}</h2>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 shadow-lg" style="backdrop-filter: blur(10px);">
                        <i class="bi bi-file-check" style="font-size: 2rem;"></i>
                    </div>
                </div>
                @if($pendingValidations > 0)
                <div class="small opacity-90 fw-semibold position-relative">
                    <i class="bi bi-clock-history me-1"></i>
                    {{ $pendingValidations }} en attente
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Taux de réussite --}}
    <div class="col-xl-3 col-md-6">
        <div class="kpi-card-modern h-100" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); border: none;">
            <div class="card-body text-white position-relative overflow-hidden">
                <div class="kpi-pattern"></div>
                <div class="d-flex justify-content-between align-items-start mb-3 position-relative">
                    <div>
                        <p class="mb-1 opacity-90 small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">Taux de réussite</p>
                        <h2 class="mb-0 fw-bold" style="font-size: 2.5rem; line-height: 1.2;">{{ $successRate }}%</h2>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 shadow-lg" style="backdrop-filter: blur(10px);">
                        <i class="bi bi-check-circle-fill" style="font-size: 2rem;"></i>
                    </div>
                </div>
                <div class="small opacity-90 fw-semibold position-relative">
                    {{ $completedMeetings }} réunions terminées
                </div>
            </div>
        </div>
    </div>
</div>

{{-- STATISTIQUES SECONDAIRES --}}
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="modern-card h-100">
            <div class="modern-card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="kpi-icon" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);">
                            <i class="bi bi-person-check text-primary"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="kpi-value mb-0">{{ $activeParticipants }}</h5>
                        <small class="kpi-label">Participants actifs</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="modern-card h-100">
            <div class="modern-card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="kpi-icon" style="background: linear-gradient(135deg, rgba(67, 233, 123, 0.1) 0%, rgba(56, 249, 215, 0.1) 100%);">
                            <i class="bi bi-people text-success"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="kpi-value mb-0">{{ $totalUsers }}</h5>
                        <small class="kpi-label">Utilisateurs actifs</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="modern-card h-100">
            <div class="modern-card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="kpi-icon" style="background: linear-gradient(135deg, rgba(240, 147, 251, 0.1) 0%, rgba(245, 87, 108, 0.1) 100%);">
                            <i class="bi bi-door-open text-warning"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="kpi-value mb-0">{{ $activeRooms }}/{{ $totalRooms }}</h5>
                        <small class="kpi-label">Salles disponibles</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="modern-card h-100">
            <div class="modern-card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="kpi-icon" style="background: linear-gradient(135deg, rgba(79, 172, 254, 0.1) 0%, rgba(0, 242, 254, 0.1) 100%);">
                            <i class="bi bi-file-earmark-text text-info"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="kpi-value mb-0">{{ $totalDocuments }}</h5>
                        <small class="kpi-label">Documents totaux</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ACTIONS RAPIDES --}}
<div class="modern-card mb-4">
    <div class="modern-card-header">
        <h5 class="modern-card-title">
            <i class="bi bi-lightning-charge-fill"></i>
            Actions rapides
        </h5>
    </div>
    <div class="modern-card-body">
        <div class="d-flex flex-wrap gap-2">
            @can('create', App\Models\Meeting::class)
            <a href="{{ route('meetings.create') }}" class="btn btn-modern btn-modern-primary">
                <i class="bi bi-plus-circle"></i> Nouvelle réunion
            </a>
            @endcan
            @can('create', App\Models\Document::class)
            <a href="{{ route('documents.create') }}" class="btn btn-modern btn-modern-secondary">
                <i class="bi bi-upload"></i> Ajouter un document
            </a>
            @endcan
            <a href="{{ route('calendar.index') }}" class="btn btn-modern btn-modern-success">
                <i class="bi bi-calendar3"></i> Voir le calendrier
            </a>
            <a href="{{ route('reports.index') }}" class="btn btn-modern btn-modern-secondary">
                <i class="bi bi-graph-up"></i> Rapports
            </a>
        </div>
    </div>
</div>

{{-- GRAPHIQUES ET ANALYSES --}}
<div class="row g-4 mb-4">
    {{-- Graphique : Réunions par mois --}}
    <div class="col-lg-8">
        <div class="modern-card h-100">
            <div class="modern-card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="modern-card-title">
                        <i class="bi bi-bar-chart-fill"></i>
                        Évolution des réunions (12 derniers mois)
                    </h5>
                    <span class="badge-modern badge-modern-primary">Année {{ now()->year }}</span>
                </div>
            </div>
            <div class="modern-card-body">
                <canvas id="meetingsByMonthChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>

    {{-- Graphique : Réunions par type --}}
    <div class="col-lg-4">
        <div class="modern-card h-100">
            <div class="modern-card-header">
                <h5 class="modern-card-title">
                    <i class="bi bi-pie-chart-fill"></i>
                    Répartition par type
                </h5>
            </div>
            <div class="modern-card-body">
                @if(!empty($chartMeetingsByTypeLabels) && count($chartMeetingsByTypeLabels) > 0)
                    <canvas id="meetingsByTypeChart" style="max-height: 300px;"></canvas>
                @else
                    <div class="empty-state">
                        <i class="bi bi-inbox empty-state-icon"></i>
                        <div class="empty-state-title">Aucune donnée</div>
                        <div class="empty-state-text">Aucune donnée disponible pour le moment</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- GRAPHIQUES SUPPLÉMENTAIRES --}}
<div class="row g-4 mb-4">
    {{-- Tendance quotidienne --}}
    <div class="col-lg-6">
        <div class="modern-card h-100">
            <div class="modern-card-header">
                <h5 class="modern-card-title">
                    <i class="bi bi-graph-up-arrow"></i>
                    Tendance (7 derniers jours)
                </h5>
            </div>
            <div class="modern-card-body">
                <canvas id="dailyTrendChart" style="max-height: 250px;"></canvas>
            </div>
        </div>
    </div>

    {{-- Statut des documents --}}
    <div class="col-lg-6">
        <div class="modern-card h-100">
            <div class="modern-card-header">
                <h5 class="modern-card-title">
                    <i class="bi bi-file-earmark-check"></i>
                    Statut des documents
                </h5>
            </div>
            <div class="modern-card-body">
                @if($chartDocumentsByStatus->count() > 0)
                    <canvas id="documentsStatusChart" style="max-height: 250px;"></canvas>
                @else
                    <div class="empty-state">
                        <i class="bi bi-inbox empty-state-icon"></i>
                        <div class="empty-state-title">Aucun document</div>
                        <div class="empty-state-text">Aucun document enregistré pour le moment</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- RÉUNIONS RÉCENTES ET PROCHAINES --}}
<div class="row g-4">
    {{-- Réunions récentes --}}
    <div class="col-lg-8">
        <div class="modern-card h-100">
            <div class="modern-card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="modern-card-title">
                        <i class="bi bi-clock-history"></i>
                        Réunions récentes
                    </h5>
                    <a href="{{ route('meetings.index') }}" class="btn btn-sm btn-modern btn-modern-secondary">
                        Voir toutes <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            <div class="modern-card-body p-0">
                <div class="meeting-list-modern">
                    @forelse($recentMeetings as $meeting)
                        <a href="{{ route('meetings.show', $meeting) }}" 
                           class="meeting-item-modern">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <div class="meeting-icon-modern">
                                            <i class="bi bi-calendar-event"></i>
                                        </div>
                                        <h6 class="mb-0 fw-semibold meeting-title-modern">{{ $meeting->title }}</h6>
                                    </div>
                                    <div class="meeting-meta-modern">
                                        <span class="meeting-meta-item">
                                            <i class="bi bi-calendar3"></i>
                                            {{ $meeting->start_at?->translatedFormat('d M Y à H:i') }}
                                        </span>
                                        @if($meeting->room && is_object($meeting->room))
                                            <span class="meeting-meta-item">
                                                <i class="bi bi-geo-alt"></i>
                                                {{ $meeting->room->name }}
                                            </span>
                                        @endif
                                        @if($meeting->meetingType)
                                            <span class="badge-modern badge-modern-primary">
                                                {{ $meeting->meetingType->name }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-shrink-0 ms-3">
                                    <span class="badge-modern
                                        @switch($meeting->status)
                                            @case('terminee') badge-modern-success @break
                                            @case('annulee') badge-modern-danger @break
                                            @case('en_cours') badge-modern-info @break
                                            @default badge-modern-secondary
                                        @endswitch">
                                        {{ ucfirst($meeting->status ?? 'N/A') }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="empty-state">
                            <i class="bi bi-inbox empty-state-icon"></i>
                            <div class="empty-state-title">Aucune réunion récente</div>
                            <div class="empty-state-text">Aucune réunion récente à afficher</div>
                        </div>
                    @endforelse
                </div>
                
                @if($recentMeetings->hasPages())
                    <div class="modern-card-footer">
                        <div class="small text-muted">
                            Affichage de {{ $recentMeetings->firstItem() }} à {{ $recentMeetings->lastItem() }} 
                            sur {{ $recentMeetings->total() }} réunion{{ $recentMeetings->total() > 1 ? 's' : '' }}
                        </div>
                        <div class="pagination-modern">
                            {{ $recentMeetings->appends(request()->except('recent_page'))->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Prochaines réunions --}}
    <div class="col-lg-4">
        <div class="modern-card h-100">
            <div class="modern-card-header">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="modern-card-title">
                        <i class="bi bi-calendar-event-fill"></i>
                        Prochaines réunions
                    </h5>
                </div>
                <div class="btn-group btn-group-sm w-100" role="group">
                    <a href="{{ route('dashboard', ['period' => 'today']) }}"
                       class="btn btn-outline-primary @if($period === 'today') active @endif">
                        Aujourd'hui
                    </a>
                    <a href="{{ route('dashboard', ['period' => 'week']) }}"
                       class="btn btn-outline-primary @if($period === 'week') active @endif">
                        Semaine
                    </a>
                    <a href="{{ route('dashboard', ['period' => 'month']) }}"
                       class="btn btn-outline-primary @if($period === 'month') active @endif">
                        Mois
                    </a>
                </div>
            </div>
            <div class="modern-card-body p-0">
                <div class="meeting-list-modern">
                    @forelse($upcomingMeetings as $meeting)
                        <a href="{{ route('meetings.show', $meeting) }}" 
                           class="meeting-item-modern">
                            <div class="d-flex align-items-start">
                                <div class="meeting-icon-modern upcoming">
                                    <i class="bi bi-calendar-event"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 small fw-semibold meeting-title-modern">{{ Str::limit($meeting->title, 40) }}</h6>
                                    <p class="mb-0 text-muted small">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ $meeting->start_at?->format('d/m H:i') }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="empty-state">
                            <i class="bi bi-calendar-x empty-state-icon"></i>
                            <div class="empty-state-title">Aucune réunion prévue</div>
                            <div class="empty-state-text">Aucune réunion prévue pour cette période</div>
                        </div>
                    @endforelse
                </div>
                
                @if($upcomingMeetings->hasPages())
                    <div class="modern-card-footer">
                        <div class="small text-muted">
                            Affichage de {{ $upcomingMeetings->firstItem() }} à {{ $upcomingMeetings->lastItem() }} 
                            sur {{ $upcomingMeetings->total() }} réunion{{ $upcomingMeetings->total() > 1 ? 's' : '' }}
                        </div>
                        <div class="pagination-modern">
                            {{ $upcomingMeetings->appends(request()->except('upcoming_page'))->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const chartColors = {
        primary: '#667eea',
        secondary: '#764ba2',
        success: '#43e97b',
        info: '#4facfe',
        warning: '#f093fb',
        danger: '#f5576c',
    };

    // Graphique 1: Réunions par mois
    const ctxMonth = document.getElementById('meetingsByMonthChart');
    if (ctxMonth) {
        new Chart(ctxMonth, {
            type: 'line',
            data: {
                labels: @json($chartMeetingsByMonthLabels),
                datasets: [{
                    label: 'Nombre de réunions',
                    data: @json($chartMeetingsByMonthData),
                    borderColor: chartColors.primary,
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: chartColors.primary,
                    pointBorderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        cornerRadius: 8,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            font: { size: 11 }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: { size: 11 }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // Graphique 2: Réunions par type
    const ctxType = document.getElementById('meetingsByTypeChart');
    if (ctxType) {
        new Chart(ctxType, {
            type: 'doughnut',
            data: {
                labels: @json($chartMeetingsByTypeLabels),
                datasets: [{
                    data: @json($chartMeetingsByTypeData),
                    backgroundColor: [
                        chartColors.primary,
                        chartColors.secondary,
                        chartColors.success,
                        chartColors.info,
                        chartColors.warning,
                        chartColors.danger,
                    ],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: { size: 12 },
                            usePointStyle: true,
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        cornerRadius: 8,
                    }
                }
            }
        });
    }

    // Graphique 3: Tendance quotidienne
    const ctxDaily = document.getElementById('dailyTrendChart');
    if (ctxDaily) {
        new Chart(ctxDaily, {
            type: 'bar',
            data: {
                labels: @json($chartDailyLabels),
                datasets: [{
                    label: 'Réunions',
                    data: @json($chartDailyData),
                    backgroundColor: chartColors.info,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        cornerRadius: 8,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            font: { size: 11 }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: { size: 11 }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // Graphique 4: Statut des documents
    const ctxDocs = document.getElementById('documentsStatusChart');
    if (ctxDocs) {
        const statusData = @json($chartDocumentsByStatus);
        const statusLabels = {
            'draft': 'Brouillon',
            'pending': 'En attente',
            'approved': 'Approuvé',
            'rejected': 'Rejeté',
            'archived': 'Archivé'
        };
        
        new Chart(ctxDocs, {
            type: 'pie',
            data: {
                labels: Object.keys(statusData).map(key => statusLabels[key] || key),
                datasets: [{
                    data: Object.values(statusData),
                    backgroundColor: [
                        '#6c757d',
                        '#ffc107',
                        '#28a745',
                        '#dc3545',
                        '#17a2b8',
                    ],
                    borderWidth: 2,
                    borderColor: '#fff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: { size: 12 },
                            usePointStyle: true,
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        cornerRadius: 8,
                    }
                }
            }
        });
    }
});
</script>
@endpush

@endsection
