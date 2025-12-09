<?php $__env->startSection('title', 'Tableau de bord'); ?>

<?php $__env->startSection('content'); ?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active">Tableau de bord</li>
    </ol>
</nav>


<div class="modern-card mb-4">
    <div class="modern-card-body">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h3 class="page-title mb-2">
                    <i class="bi bi-speedometer2 text-primary me-2"></i>
                    Bienvenue, <?php echo e($user->name ?? 'Utilisateur'); ?>

                </h3>
                <p class="text-muted mb-0">
                    <i class="bi bi-calendar3 me-1"></i>
                    <?php echo e(now()->translatedFormat('l d F Y')); ?> • Tableau de bord SGRS-CEEAC
                </p>
            </div>
            <div class="text-end">
                <div class="badge-modern badge-modern-primary px-3 py-2">
                    <i class="bi bi-clock me-1"></i>
                    <?php echo e(now()->format('H:i')); ?>

                </div>
            </div>
        </div>
    </div>
</div>


<div class="row g-4 mb-4">
    
    <div class="col-xl-3 col-md-6">
        <div class="kpi-card-modern h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
            <div class="card-body text-white position-relative overflow-hidden">
                <div class="kpi-pattern"></div>
                <div class="d-flex justify-content-between align-items-start mb-3 position-relative">
                    <div>
                        <p class="mb-1 opacity-90 small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">Réunions ce mois</p>
                        <h2 class="mb-0 fw-bold" style="font-size: 2.5rem; line-height: 1.2;"><?php echo e($meetingsThisMonth); ?></h2>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 shadow-lg" style="backdrop-filter: blur(10px);">
                        <i class="bi bi-calendar-week" style="font-size: 2rem;"></i>
                    </div>
                </div>
                <?php if($meetingsGrowth != 0): ?>
                <div class="d-flex align-items-center position-relative">
                    <i class="bi bi-arrow-<?php echo e($meetingsGrowth > 0 ? 'up' : 'down'); ?>-right me-1 fs-5"></i>
                    <span class="small fw-semibold"><?php echo e(abs($meetingsGrowth)); ?>% vs mois dernier</span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div class="col-xl-3 col-md-6">
        <div class="kpi-card-modern h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border: none;">
            <div class="card-body text-white position-relative overflow-hidden">
                <div class="kpi-pattern"></div>
                <div class="d-flex justify-content-between align-items-start mb-3 position-relative">
                    <div>
                        <p class="mb-1 opacity-90 small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">Taux de participation</p>
                        <h2 class="mb-0 fw-bold" style="font-size: 2.5rem; line-height: 1.2;"><?php echo e($participationRate); ?>%</h2>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 shadow-lg" style="backdrop-filter: blur(10px);">
                        <i class="bi bi-people-fill" style="font-size: 2rem;"></i>
                    </div>
                </div>
                <div class="small opacity-90 fw-semibold position-relative">
                    <?php echo e($confirmedInvitations ?? 0); ?> confirmations
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-xl-3 col-md-6">
        <div class="kpi-card-modern h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border: none;">
            <div class="card-body text-white position-relative overflow-hidden">
                <div class="kpi-pattern"></div>
                <div class="d-flex justify-content-between align-items-start mb-3 position-relative">
                    <div>
                        <p class="mb-1 opacity-90 small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">Documents validés</p>
                        <h2 class="mb-0 fw-bold" style="font-size: 2.5rem; line-height: 1.2;"><?php echo e($approvedDocuments); ?></h2>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 shadow-lg" style="backdrop-filter: blur(10px);">
                        <i class="bi bi-file-check" style="font-size: 2rem;"></i>
                    </div>
                </div>
                <?php if($pendingValidations > 0): ?>
                <div class="small opacity-90 fw-semibold position-relative">
                    <i class="bi bi-clock-history me-1"></i>
                    <?php echo e($pendingValidations); ?> en attente
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div class="col-xl-3 col-md-6">
        <div class="kpi-card-modern h-100" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); border: none;">
            <div class="card-body text-white position-relative overflow-hidden">
                <div class="kpi-pattern"></div>
                <div class="d-flex justify-content-between align-items-start mb-3 position-relative">
                    <div>
                        <p class="mb-1 opacity-90 small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">Taux de réussite</p>
                        <h2 class="mb-0 fw-bold" style="font-size: 2.5rem; line-height: 1.2;"><?php echo e($successRate); ?>%</h2>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 shadow-lg" style="backdrop-filter: blur(10px);">
                        <i class="bi bi-check-circle-fill" style="font-size: 2rem;"></i>
                    </div>
                </div>
                <div class="small opacity-90 fw-semibold position-relative">
                    <?php echo e($completedMeetings); ?> réunions terminées
                </div>
            </div>
        </div>
    </div>
</div>


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
                        <h5 class="kpi-value mb-0"><?php echo e($activeParticipants); ?></h5>
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
                        <h5 class="kpi-value mb-0"><?php echo e($totalUsers); ?></h5>
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
                        <h5 class="kpi-value mb-0"><?php echo e($activeRooms); ?>/<?php echo e($totalRooms); ?></h5>
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
                        <h5 class="kpi-value mb-0"><?php echo e($totalDocuments); ?></h5>
                        <small class="kpi-label">Documents totaux</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modern-card mb-4">
    <div class="modern-card-header">
        <h5 class="modern-card-title">
            <i class="bi bi-lightning-charge-fill"></i>
            Actions rapides
        </h5>
    </div>
    <div class="modern-card-body">
        <div class="d-flex flex-wrap gap-2">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Meeting::class)): ?>
            <a href="<?php echo e(route('meetings.create')); ?>" class="btn btn-modern btn-modern-primary">
                <i class="bi bi-plus-circle"></i> Nouvelle réunion
            </a>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Document::class)): ?>
            <a href="<?php echo e(route('documents.create')); ?>" class="btn btn-modern btn-modern-secondary">
                <i class="bi bi-upload"></i> Ajouter un document
            </a>
            <?php endif; ?>
            <a href="<?php echo e(route('calendar.index')); ?>" class="btn btn-modern btn-modern-success">
                <i class="bi bi-calendar3"></i> Voir le calendrier
            </a>
            <a href="<?php echo e(route('reports.index')); ?>" class="btn btn-modern btn-modern-secondary">
                <i class="bi bi-graph-up"></i> Rapports
            </a>
        </div>
    </div>
</div>


<div class="row g-4 mb-4">
    
    <div class="col-lg-8">
        <div class="modern-card h-100">
            <div class="modern-card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="modern-card-title">
                        <i class="bi bi-bar-chart-fill"></i>
                        Évolution des réunions (12 derniers mois)
                    </h5>
                    <span class="badge-modern badge-modern-primary">Année <?php echo e(now()->year); ?></span>
                </div>
            </div>
            <div class="modern-card-body">
                <canvas id="meetingsByMonthChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>

    
    <div class="col-lg-4">
        <div class="modern-card h-100">
            <div class="modern-card-header">
                <h5 class="modern-card-title">
                    <i class="bi bi-pie-chart-fill"></i>
                    Répartition par type
                </h5>
            </div>
            <div class="modern-card-body">
                <?php if(!empty($chartMeetingsByTypeLabels) && count($chartMeetingsByTypeLabels) > 0): ?>
                    <canvas id="meetingsByTypeChart" style="max-height: 300px;"></canvas>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="bi bi-inbox empty-state-icon"></i>
                        <div class="empty-state-title">Aucune donnée</div>
                        <div class="empty-state-text">Aucune donnée disponible pour le moment</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<div class="row g-4 mb-4">
    
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

    
    <div class="col-lg-6">
        <div class="modern-card h-100">
            <div class="modern-card-header">
                <h5 class="modern-card-title">
                    <i class="bi bi-file-earmark-check"></i>
                    Statut des documents
                </h5>
            </div>
            <div class="modern-card-body">
                <?php if($chartDocumentsByStatus->count() > 0): ?>
                    <canvas id="documentsStatusChart" style="max-height: 250px;"></canvas>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="bi bi-inbox empty-state-icon"></i>
                        <div class="empty-state-title">Aucun document</div>
                        <div class="empty-state-text">Aucun document enregistré pour le moment</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<div class="row g-4">
    
    <div class="col-lg-8">
        <div class="modern-card h-100">
            <div class="modern-card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="modern-card-title">
                        <i class="bi bi-clock-history"></i>
                        Réunions récentes
                    </h5>
                    <a href="<?php echo e(route('meetings.index')); ?>" class="btn btn-sm btn-modern btn-modern-secondary">
                        Voir toutes <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            <div class="modern-card-body p-0">
                <div class="meeting-list-modern">
                    <?php $__empty_1 = true; $__currentLoopData = $recentMeetings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meeting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <a href="<?php echo e(route('meetings.show', $meeting)); ?>" 
                           class="meeting-item-modern">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <div class="meeting-icon-modern">
                                            <i class="bi bi-calendar-event"></i>
                                        </div>
                                        <h6 class="mb-0 fw-semibold meeting-title-modern"><?php echo e($meeting->title); ?></h6>
                                    </div>
                                    <div class="meeting-meta-modern">
                                        <span class="meeting-meta-item">
                                            <i class="bi bi-calendar3"></i>
                                            <?php echo e($meeting->start_at?->translatedFormat('d M Y à H:i')); ?>

                                        </span>
                                        <?php if($meeting->room && is_object($meeting->room)): ?>
                                            <span class="meeting-meta-item">
                                                <i class="bi bi-geo-alt"></i>
                                                <?php echo e($meeting->room->name); ?>

                                            </span>
                                        <?php endif; ?>
                                        <?php if($meeting->meetingType): ?>
                                            <span class="badge-modern badge-modern-primary">
                                                <?php echo e($meeting->meetingType->name); ?>

                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="flex-shrink-0 ms-3">
                                    <span class="badge-modern
                                        <?php switch($meeting->status):
                                            case ('terminee'): ?> badge-modern-success <?php break; ?>
                                            <?php case ('annulee'): ?> badge-modern-danger <?php break; ?>
                                            <?php case ('en_cours'): ?> badge-modern-info <?php break; ?>
                                            <?php default: ?> badge-modern-secondary
                                        <?php endswitch; ?>">
                                        <?php echo e(ucfirst($meeting->status ?? 'N/A')); ?>

                                    </span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="empty-state">
                            <i class="bi bi-inbox empty-state-icon"></i>
                            <div class="empty-state-title">Aucune réunion récente</div>
                            <div class="empty-state-text">Aucune réunion récente à afficher</div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if($recentMeetings->hasPages()): ?>
                    <div class="modern-card-footer">
                        <div class="small text-muted">
                            Affichage de <?php echo e($recentMeetings->firstItem()); ?> à <?php echo e($recentMeetings->lastItem()); ?> 
                            sur <?php echo e($recentMeetings->total()); ?> réunion<?php echo e($recentMeetings->total() > 1 ? 's' : ''); ?>

                        </div>
                        <div class="pagination-modern">
                            <?php echo e($recentMeetings->appends(request()->except('recent_page'))->links()); ?>

                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
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
                    <a href="<?php echo e(route('dashboard', ['period' => 'today'])); ?>"
                       class="btn btn-outline-primary <?php if($period === 'today'): ?> active <?php endif; ?>">
                        Aujourd'hui
                    </a>
                    <a href="<?php echo e(route('dashboard', ['period' => 'week'])); ?>"
                       class="btn btn-outline-primary <?php if($period === 'week'): ?> active <?php endif; ?>">
                        Semaine
                    </a>
                    <a href="<?php echo e(route('dashboard', ['period' => 'month'])); ?>"
                       class="btn btn-outline-primary <?php if($period === 'month'): ?> active <?php endif; ?>">
                        Mois
                    </a>
                </div>
            </div>
            <div class="modern-card-body p-0">
                <div class="meeting-list-modern">
                    <?php $__empty_1 = true; $__currentLoopData = $upcomingMeetings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meeting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <a href="<?php echo e(route('meetings.show', $meeting)); ?>" 
                           class="meeting-item-modern">
                            <div class="d-flex align-items-start">
                                <div class="meeting-icon-modern upcoming">
                                    <i class="bi bi-calendar-event"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 small fw-semibold meeting-title-modern"><?php echo e(Str::limit($meeting->title, 40)); ?></h6>
                                    <p class="mb-0 text-muted small">
                                        <i class="bi bi-clock me-1"></i>
                                        <?php echo e($meeting->start_at?->format('d/m H:i')); ?>

                                    </p>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="empty-state">
                            <i class="bi bi-calendar-x empty-state-icon"></i>
                            <div class="empty-state-title">Aucune réunion prévue</div>
                            <div class="empty-state-text">Aucune réunion prévue pour cette période</div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if($upcomingMeetings->hasPages()): ?>
                    <div class="modern-card-footer">
                        <div class="small text-muted">
                            Affichage de <?php echo e($upcomingMeetings->firstItem()); ?> à <?php echo e($upcomingMeetings->lastItem()); ?> 
                            sur <?php echo e($upcomingMeetings->total()); ?> réunion<?php echo e($upcomingMeetings->total() > 1 ? 's' : ''); ?>

                        </div>
                        <div class="pagination-modern">
                            <?php echo e($upcomingMeetings->appends(request()->except('upcoming_page'))->links()); ?>

                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
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
                labels: <?php echo json_encode($chartMeetingsByMonthLabels, 15, 512) ?>,
                datasets: [{
                    label: 'Nombre de réunions',
                    data: <?php echo json_encode($chartMeetingsByMonthData, 15, 512) ?>,
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
                labels: <?php echo json_encode($chartMeetingsByTypeLabels, 15, 512) ?>,
                datasets: [{
                    data: <?php echo json_encode($chartMeetingsByTypeData, 15, 512) ?>,
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
                labels: <?php echo json_encode($chartDailyLabels, 15, 512) ?>,
                datasets: [{
                    label: 'Réunions',
                    data: <?php echo json_encode($chartDailyData, 15, 512) ?>,
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
        const statusData = <?php echo json_encode($chartDocumentsByStatus, 15, 512) ?>;
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
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views/dashboard/index.blade.php ENDPATH**/ ?>