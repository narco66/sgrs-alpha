<?php $__env->startSection('content'); ?>
<?php
    use Carbon\Carbon;
    use Illuminate\Support\Str;

    $today = Carbon::today();
    $prevWeek = $startOfWeek->copy()->subWeek();
    $nextWeek = $startOfWeek->copy()->addWeek();
    $periodLabel = $startOfWeek->locale('fr_FR')->translatedFormat('d F') . ' - ' . $endOfWeek->locale('fr_FR')->translatedFormat('d F Y');
    $dayNames = ['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'];
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-1 fw-semibold">Calendrier Hebdomadaire</h3>
        <div class="small">
            <a href="<?php echo e(route('dashboard')); ?>" class="text-decoration-none text-muted">Accueil</a>
            <span class="text-muted">/</span>
            <a href="<?php echo e(route('calendar.index')); ?>" class="text-decoration-none text-muted">Calendrier</a>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="<?php echo e(route('calendar.day', ['date' => $baseDate->toDateString()])); ?>" class="btn btn-outline-primary btn-sm">Jour</a>
        <a href="<?php echo e(route('calendar.week', ['date' => $baseDate->toDateString()])); ?>" class="btn btn-primary btn-sm">Semaine</a>
        <a href="<?php echo e(route('calendar.month', ['date' => $baseDate->toDateString()])); ?>" class="btn btn-outline-primary btn-sm">Mois</a>
        <a href="<?php echo e(route('calendar.year', ['year' => $baseDate->year])); ?>" class="btn btn-outline-primary btn-sm">Année</a>
    </div>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-2">
            <a href="<?php echo e(route('calendar.week', ['date' => $prevWeek->toDateString()])); ?>" class="btn btn-light border">
                <i class="bi bi-chevron-left"></i>
            </a>
            <span class="fw-semibold fs-6"><?php echo e($periodLabel); ?></span>
            <a href="<?php echo e(route('calendar.week', ['date' => $nextWeek->toDateString()])); ?>" class="btn btn-light border">
                <i class="bi bi-chevron-right"></i>
            </a>
        </div>
        <a href="<?php echo e(route('calendar.week', ['date' => $today->toDateString()])); ?>" class="btn btn-outline-primary btn-sm">Aujourd'hui</a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="calendar-week">
            <div class="calendar-week-header">
                <div class="calendar-week-time-col"></div>
                <?php for($d = 0; $d < 7; $d++): ?>
                    <?php
                        $current = $startOfWeek->copy()->addDays($d);
                        $isToday = $current->isSameDay($today);
                    ?>
                    <div class="calendar-week-day <?php echo e($isToday ? 'calendar-week-day-today' : ''); ?>">
                        <div class="fw-semibold"><?php echo e($dayNames[$d]); ?></div>
                        <div class="text-muted small"><?php echo e($current->format('d/m')); ?></div>
                    </div>
                <?php endfor; ?>
            </div>
            <div class="calendar-week-body">
                <?php for($hour = 0; $hour < 24; $hour++): ?>
                    <div class="calendar-week-row">
                        <div class="calendar-week-time"><?php echo e(str_pad($hour, 2, '0', STR_PAD_LEFT)); ?>h</div>
                        <?php for($d = 0; $d < 7; $d++): ?>
                            <?php
                                $current = $startOfWeek->copy()->addDays($d);
                                $dayMeetings = $meetingsByDay[$current->toDateString()] ?? collect();
                                $items = $dayMeetings->filter(fn($m) => Carbon::parse($m->start_at)->format('H') === str_pad($hour, 2, '0', STR_PAD_LEFT));
                                $isToday = $current->isSameDay($today);
                            ?>
                            <div class="calendar-week-cell <?php echo e($isToday ? 'calendar-week-cell-today' : ''); ?>">
                                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meeting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                    <a href="<?php echo e(route('meetings.show', $meeting)); ?>" class="calendar-week-event bg-<?php echo e($color); ?>">
                                        <div class="small fw-semibold"><?php echo e($start->format('H:i')); ?> <?php if($end): ?>- <?php echo e($end->format('H:i')); ?><?php endif; ?></div>
                                        <div class="small"><?php echo e(Str::limit($meeting->title, 40)); ?></div>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endfor; ?>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</div>

<?php
    $upcoming = $meetings->sortBy('start_at')->take(5);
?>

<div class="row g-3 mt-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-semibold">À venir</div>
                    <div class="text-muted small">Prochaines réunions de la semaine</div>
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
                    <p class="text-muted mb-0">Aucune réunion prévue pour cette semaine.</p>
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
                    <li class="mb-2"><i class="bi bi-info-circle me-1 text-primary"></i>Planifiez les ressources pour les jours chargés.</li>
                    <li class="mb-2"><i class="bi bi-bell me-1 text-warning"></i>Anticipez les relances pour les réunions critiques.</li>
                    <li><i class="bi bi-clipboard-check me-1 text-success"></i>Regroupez les points logistiques en une seule liste.</li>
                </ul>
                <textarea class="form-control" rows="4" placeholder="Ajoutez ici vos notes internes..."></textarea>
                <small class="text-muted d-block mt-2">Ces notes sont locales à cette page.</small>
            </div>
        </div>
    </div>
</div>

<style>
    .calendar-week { width: 100%; }
    .calendar-week-header { display: grid; grid-template-columns: 70px repeat(7, 1fr); background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
    .calendar-week-day { padding: 0.75rem; text-align: center; border-left: 1px solid #e2e8f0; }
    .calendar-week-day:first-of-type { border-left: none; }
    .calendar-week-day-today { background: #e6f0ff; }
    .calendar-week-body { display: flex; flex-direction: column; }
    .calendar-week-row { display: grid; grid-template-columns: 70px repeat(7, 1fr); min-height: 70px; border-bottom: 1px solid #f1f5f9; }
    .calendar-week-time { padding: 0.75rem; font-weight: 600; color: #64748b; background: #f8fafc; }
    .calendar-week-cell { border-left: 1px solid #f1f5f9; padding: 0.5rem; position: relative; }
    .calendar-week-cell:first-of-type { border-left: none; }
    .calendar-week-cell-today { background: #f8fafc; }
    .calendar-week-event { display: block; color: #fff; padding: 0.6rem 0.7rem; border-radius: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.08); text-decoration: none; margin-bottom: 0.35rem; }
    @media (max-width: 992px) { .calendar-week-header { grid-template-columns: 60px repeat(7, 1fr); } .calendar-week-row { grid-template-columns: 60px repeat(7, 1fr); } .calendar-week-time { font-size: 0.9rem; } }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views/calendar/week.blade.php ENDPATH**/ ?>