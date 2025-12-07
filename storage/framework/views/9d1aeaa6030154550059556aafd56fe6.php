<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Délégation - <?php echo e($delegation->title); ?></title>
    <style>
        @page { margin: 110px 30px 70px 30px; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #111827; margin: 0; padding: 0 20px; box-sizing: border-box; }
        h1 { margin: 0 0 12px; font-size: 20px; color: #0f172a; }
        h2 { margin: 18px 0 8px; font-size: 16px; border-bottom: 1px solid #e5e7eb; padding-bottom: 6px; }
        p { margin: 2px 0 6px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #e5e7eb; padding: 6px 8px; text-align: left; }
        th { background: #f8fafc; }
        .grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px 16px; margin-top: 8px; }
        .muted { color: #6b7280; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 10px; font-size: 11px; }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-secondary { background: #e5e7eb; color: #374151; }
        .header-table { width: 100%; margin-bottom: 14px; }
        .footer { font-size: 11px; color: #6b7280; text-align: center; border-top: 1px solid #e5e7eb; padding-top: 6px; margin-top: 20px; }
    </style>
</head>
<body>
    <?php
        $logoPath = public_path('images/logo-ceeac.png');
        if (! file_exists($logoPath)) {
            $alt = glob(public_path('images/logo*ceeac.png'));
            $logoPath = $alt[0] ?? null;
        }
    ?>
    <table class="header-table">
        <tr>
            <td style="width:70px;">
                <?php if($logoPath): ?>
                    <img src="<?php echo e($logoPath); ?>" alt="CEEAC" style="height:50px;">
                <?php endif; ?>
            </td>
            <td style="text-align:right; font-size:13px; color:#0f172a; font-weight:bold;">
                SGRS-CEEAC: Système de Gestion des Réunions Statutaires de la CEEAC
            </td>
        </tr>
    </table>

    <h1><?php echo e($delegation->title); ?></h1>
    <p class="muted">
        <?php if($delegation->code): ?> Code : <?php echo e($delegation->code); ?> <?php endif; ?>
        <?php if($delegation->country): ?> — Pays : <?php echo e($delegation->country); ?> <?php endif; ?>
    </p>

    <div class="grid">
        <div><strong>Statut :</strong>
            <span class="badge <?php echo e($delegation->is_active ? 'badge-success' : 'badge-secondary'); ?>">
                <?php echo e($delegation->is_active ? 'Actif' : 'Inactif'); ?>

            </span>
        </div>
        <div><strong>Email contact :</strong> <?php echo e($delegation->contact_email ?? '—'); ?></div>
        <div><strong>Téléphone :</strong> <?php echo e($delegation->contact_phone ?? '—'); ?></div>
        <div><strong>Adresse :</strong> <?php echo e($delegation->address ?? '—'); ?></div>
    </div>

    <?php if($delegation->description): ?>
        <h2>Présentation</h2>
        <p><?php echo e($delegation->description); ?></p>
    <?php endif; ?>

    <h2>Réunion associée</h2>
    <?php if($delegation->meeting): ?>
        <p><strong><?php echo e($delegation->meeting->title); ?></strong></p>
        <p class="muted">
            <?php echo e($delegation->meeting->start_at?->format('d/m/Y H:i') ?? 'Date non définie'); ?>

            <?php if($delegation->meeting->room): ?>
                — Salle : <?php echo e($delegation->meeting->room->name); ?>

            <?php endif; ?>
        </p>
    <?php else: ?>
        <p class="muted">Aucune réunion liée.</p>
    <?php endif; ?>

    <h2>Participants de la délégation</h2>
    <?php if($delegation->participants->count()): ?>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $delegation->participants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($user->name); ?></td>
                        <td><?php echo e($user->email ?? '—'); ?></td>
                        <td><?php echo e($user->pivot->role ?? 'Participant'); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="muted">Aucun participant renseigné.</p>
    <?php endif; ?>

    <h2>Utilisateurs rattachés</h2>
    <?php if($delegation->users->count()): ?>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Service</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $delegation->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($user->name); ?></td>
                        <td><?php echo e($user->email ?? '—'); ?></td>
                        <td><?php echo e($user->service ?? '—'); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="muted">Aucun utilisateur rattaché.</p>
    <?php endif; ?>

    <div class="footer">
        BP:2112 Libreville-GABON Tel. +(241) 44 47 31, +(241) 44 47 34 -Email : commission@ceeac-eccas.org
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\delegations\pdf.blade.php ENDPATH**/ ?>