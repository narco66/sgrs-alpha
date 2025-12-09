<?php $__env->startSection('content'); ?>
<?php
    use Carbon\Carbon;

    $today = Carbon::today();
    $prevYear = $year - 1;
    $nextYear = $year + 1;
    $monthNames = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-1 fw-semibold">Calendrier Annuel</h3>
        <div class="small">
            <a href="<?php echo e(route('dashboard')); ?>" class="text-decoration-none text-muted">Accueil</a>
            <span class="text-muted">/</span>
            <a href="<?php echo e(route('calendar.index')); ?>" class="text-decoration-none text-muted">Calendrier</a>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="<?php echo e(route('calendar.day', ['date' => Carbon::create($year, 1, 1)->toDateString()])); ?>" class="btn btn-outline-primary btn-sm">Jour</a>
        <a href="<?php echo e(route('calendar.week', ['date' => Carbon::create($year, 1, 1)->toDateString()])); ?>" class="btn btn-outline-primary btn-sm">Semaine</a>
        <a href="<?php echo e(route('calendar.month', ['date' => Carbon::create($year, 1, 1)->toDateString()])); ?>" class="btn btn-outline-primary btn-sm">Mois</a>
        <a href="<?php echo e(route('calendar.year', ['year' => $year])); ?>" class="btn btn-primary btn-sm">Année</a>
    </div>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-2">
            <a href="<?php echo e(route('calendar.year', ['year' => $prevYear])); ?>" class="btn btn-light border">
                <i class="bi bi-chevron-left"></i>
            </a>
            <span class="fw-semibold fs-6"><?php echo e($year); ?></span>
            <a href="<?php echo e(route('calendar.year', ['year' => $nextYear])); ?>" class="btn btn-light border">
                <i class="bi bi-chevron-right"></i>
            </a>
        </div>
        <a href="<?php echo e(route('calendar.year', ['year' => $today->year])); ?>" class="btn btn-outline-primary btn-sm">Aujourd'hui</a>
    </div>
</div>

<?php
    $upcoming = $meetings->sortBy('start_at')->take(5);
?>

<div class="calendar-year-grid">
    <?php for($month = 1; $month <= 12; $month++): ?>
        <?php
            $monthDate = Carbon::create($year, $month, 1);
            $startGrid = $monthDate->copy()->startOfMonth()->startOfWeek(Carbon::MONDAY);
            $endGrid = $monthDate->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);
            $monthMeetings = $meetingsByMonth[$month] ?? collect();
            $meetingsByDate = $monthMeetings->groupBy(fn($m) => Carbon::parse($m->start_at)->toDateString());
        ?>
        <div class="card border-0 shadow-sm calendar-year-card">
            <div class="card-body p-3">
                <div class="calendar-year-month-header">
                    <span class="text-white fw-semibold"><?php echo e($monthNames[$month-1]); ?></span>
                    <span class="badge bg-light text-dark"><?php echo e($monthMeetings->count()); ?> réunion(s)</span>
                </div>
                <div class="calendar-year-mini-grid">
                    <?php $__currentLoopData = ['Lu','Ma','Me','Je','Ve','Sa','Di']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $abbr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="calendar-year-mini-day text-muted small fw-semibold"><?php echo e($abbr); ?></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php for($date = $startGrid->copy(); $date->lte($endGrid); $date->addDay()): ?>
                        <?php
                            $isCurrentMonth = $date->isSameMonth($monthDate);
                            $isToday = $date->isSameDay($today);
                            $dayMeetings = $meetingsByDate[$date->toDateString()] ?? collect();
                            $statusColor = match(optional($dayMeetings->first())->status) {
                                'planifiee' => 'primary',
                                'en_cours' => 'warning',
                                'terminee' => 'success',
                                'annulee' => 'danger',
                                default => 'info',
                            };
                        ?>
                        <div class="calendar-year-mini-cell <?php echo e($isCurrentMonth ? '' : 'text-muted opacity-50'); ?> <?php echo e($isToday ? 'calendar-year-mini-today' : ''); ?>">
                            <span class="small fw-semibold"><?php echo e($date->day); ?></span>
                            <?php if($dayMeetings->isNotEmpty()): ?>
                                <span class="calendar-year-mini-dot bg-<?php echo e($statusColor); ?>"></span>
                            <?php endif; ?>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    <?php endfor; ?>
</div>

<div class="row g-3 mt-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-semibold">À venir</div>
                    <div class="text-muted small">Prochaines réunions de l’année</div>
                </div>
                <a href="<?php echo e(route('meetings.index')); ?>" class="btn btn-sm btn-outline-primary">Voir toutes</a>
            </div>
            <div class="card-body">
                <?php $__empty_1 = true; $__currentLoopData = $upcoming; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meeting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $start = Carbon::parse($meeting->start_at);
                        $end = $meeting->end_at ? Carbon::parse($meeting->end_at) : null;
                        $color = match($meeting->status) {
                            'planifiee' => 'primary',
                            'en_cours' => 'warning',
                            'terminee' => 'success',
                            'annulee' => 'danger',
                            default => 'info',
                        };
                    ?>
                    <div class="d-flex align-items-start mb-3 pb-3 border-bottom">
                        <div class="badge bg-<?php echo e($color); ?> me-3 px-3 py-2"><?php echo e($start->format('d M')); ?></div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold"><?php echo e($meeting->title); ?></div>
                            <div class="text-muted small"><?php echo e($start->format('H:i')); ?> <?php if($end): ?>- <?php echo e($end->format('H:i')); ?><?php endif; ?> • <?php echo e($meeting->committee->name ?? 'Comité'); ?></div>
                            <?php if($meeting->room?->name): ?>
                                <div class="text-muted small"><i class="bi bi-geo-alt me-1"></i><?php echo e($meeting->room->name); ?></div>
                            <?php endif; ?>
                        </div>
                        <a href="<?php echo e(route('meetings.show', $meeting)); ?>" class="btn btn-sm btn-outline-secondary">Détails</a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-muted mb-0">Aucune réunion planifiée sur cette période.</p>
                <?php endif; ?>
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
                    <li class="mb-2"><i class="bi bi-info-circle me-1 text-primary"></i>Identifiez les périodes chargées et préparez les ressources.</li>
                    <li class="mb-2"><i class="bi bi-bell me-1 text-warning"></i>Planifiez les relances et validations en amont.</li>
                    <li><i class="bi bi-clipboard-check me-1 text-success"></i>Assurez-vous que les salles et comités sont bien répartis.</li>
                </ul>
                <textarea class="form-control" rows="4" placeholder="Ajoutez ici vos notes internes..."></textarea>
                <small class="text-muted d-block mt-2">Ces notes sont locales à cette page.</small>
            </div>
        </div>
    </div>
</div>

<style>
    .calendar-year-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1rem; }
    .calendar-year-card { min-height: 240px; }
    .calendar-year-month-header { background: #2b6cb0; color: #fff; border-radius: 12px; padding: 0.65rem 0.75rem; display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem; }
    .calendar-year-mini-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 0.35rem; }
    .calendar-year-mini-day { text-align: center; font-size: 0.8rem; }
    .calendar-year-mini-cell { height: 38px; border-radius: 10px; background: #f8fafc; display: flex; align-items: center; justify-content: center; gap: 0.25rem; position: relative; }
    .calendar-year-mini-cell:hover { background: #edf2ff; }
    .calendar-year-mini-today { outline: 2px solid #2b6cb0; }
    .calendar-year-mini-dot { width: 9px; height: 9px; border-radius: 50%; display: inline-block; }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views/calendar/year.blade.php ENDPATH**/ ?>