<?php $__env->startSection('content'); ?>
<?php
    use Carbon\Carbon;
    use Illuminate\Support\Str;

    $today = Carbon::today();
    $prevDate = $baseDate->copy()->subDay();
    $nextDate = $baseDate->copy()->addDay();
    $label = $baseDate->locale('fr_FR')->translatedFormat('l j F Y');
    $meetingsByHour = $meetings->groupBy(fn($m) => Carbon::parse($m->start_at)->format('H'));
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-1 fw-semibold">Calendrier Journalier</h3>
        <div class="small">
            <a href="<?php echo e(route('dashboard')); ?>" class="text-decoration-none text-muted">Accueil</a>
            <span class="text-muted">/</span>
            <a href="<?php echo e(route('calendar.index')); ?>" class="text-decoration-none text-muted">Calendrier</a>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="<?php echo e(route('calendar.day', ['date' => $baseDate->toDateString()])); ?>" class="btn btn-primary btn-sm">Jour</a>
        <a href="<?php echo e(route('calendar.week', ['date' => $baseDate->toDateString()])); ?>" class="btn btn-outline-primary btn-sm">Semaine</a>
        <a href="<?php echo e(route('calendar.month', ['date' => $baseDate->toDateString()])); ?>" class="btn btn-outline-primary btn-sm">Mois</a>
        <a href="<?php echo e(route('calendar.year', ['year' => $baseDate->year])); ?>" class="btn btn-outline-primary btn-sm">Année</a>
    </div>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-2">
            <a href="<?php echo e(route('calendar.day', ['date' => $prevDate->toDateString()])); ?>" class="btn btn-light border">
                <i class="bi bi-chevron-left"></i>
            </a>
            <span class="fw-semibold fs-6"><?php echo e(Str::ucfirst($label)); ?></span>
            <a href="<?php echo e(route('calendar.day', ['date' => $nextDate->toDateString()])); ?>" class="btn btn-light border">
                <i class="bi bi-chevron-right"></i>
            </a>
        </div>
        <a href="<?php echo e(route('calendar.day', ['date' => $today->toDateString()])); ?>" class="btn btn-outline-primary btn-sm">Aujourd'hui</a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="calendar-day-rail">
            <?php for($hour = 0; $hour < 24; $hour++): ?>
                <?php
                    $hourKey = str_pad($hour, 2, '0', STR_PAD_LEFT);
                    $items = $meetingsByHour[$hourKey] ?? collect();
                    $isCurrent = $today->isSameDay($baseDate) && $hour === now()->hour;
                ?>
                <div class="calendar-day-row <?php echo e($isCurrent ? 'calendar-day-row-current' : ''); ?>">
                    <div class="calendar-day-time"><?php echo e($hourKey); ?>h</div>
                    <div class="calendar-day-slot">
                        <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meeting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
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
                            <a href="<?php echo e(route('meetings.show', $meeting)); ?>" class="calendar-day-meeting bg-<?php echo e($color); ?>">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold"><?php echo e($start->format('H:i')); ?> <?php if($end): ?>- <?php echo e($end->format('H:i')); ?><?php endif; ?></span>
                                    <span class="badge bg-light text-dark border ms-2"><?php echo e(Str::limit($meeting->status ?? 'Statut', 12)); ?></span>
                                </div>
                                <div class="small"><?php echo e(Str::limit($meeting->title, 60)); ?></div>
                                <?php if($meeting->committee?->name || $meeting->room?->name): ?>
                                    <div class="text-muted small mt-1 d-flex gap-2 align-items-center">
                                        <?php if($meeting->committee?->name): ?>
                                            <span><i class="bi bi-people-fill me-1"></i><?php echo e(Str::limit($meeting->committee->name, 30)); ?></span>
                                        <?php endif; ?>
                                        <?php if($meeting->room?->name): ?>
                                            <span><i class="bi bi-geo-alt me-1"></i><?php echo e(Str::limit($meeting->room->name, 24)); ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="calendar-day-empty"></div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endfor; ?>
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
                    <div class="text-muted small">Prochaines réunions de la journée</div>
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
                        <div class="badge bg-<?php echo e($color); ?> me-3 px-3 py-2"><?php echo e($start->format('H:i')); ?></div>
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
                    <p class="text-muted mb-0">Aucune réunion prévue pour cette journée.</p>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views/calendar/day.blade.php ENDPATH**/ ?>