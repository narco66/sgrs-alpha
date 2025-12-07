<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Comité d'organisation - <?php echo e($committee->name); ?></title>
    <style>
        @page { margin: 120px 30px 70px 30px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; margin: 0; color: #1f2937; padding: 0 24px; position: relative; box-sizing: border-box; }
        h1 { margin: 0 0 10px; color: #1d4ed8; }
        h2 { margin: 20px 0 8px; font-size: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #e5e7eb; padding: 8px; }
        th { background: #eef2ff; text-align: left; }
        .meta { margin: 0 0 4px; color: #6b7280; }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 6px; font-size: 11px; color: #fff; }
        .badge-primary { background: #2563eb; }
        .badge-success { background: #16a34a; }
        .badge-secondary { background: #6b7280; }
        .footer {
            font-size: 11px;
            color: #6b7280;
            text-align: center;
            border-top: 1px solid #e5e7eb;
            padding: 8px 0 0 0;
            margin-top: 24px;
        }
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
    <table width="100%" style="margin-bottom:12px;">
        <tr>
            <td style="width:70px;">
                <?php if($logoPath): ?>
                    <img src="<?php echo e($logoPath); ?>" alt="CEEAC" style="height:50px;">
                <?php endif; ?>
            </td>
            <td style="text-align:right; font-size:13px; color:#1f2937; font-weight:bold;">
                SGRS-CEEAC: Système de Gestion des Réunions Statutaires de la CEEAC
            </td>
        </tr>
    </table>
    <h1>Comité d'organisation</h1>
    <p class="meta">Nom : <strong><?php echo e($committee->name); ?></strong></p>
    <p class="meta">Réunion associée :
        <?php if($committee->meeting): ?>
            <?php echo e($committee->meeting->title); ?> (<?php echo e(optional($committee->meeting->start_at)->format('d/m/Y H:i')); ?>)
        <?php else: ?>
            N/A
        <?php endif; ?>
    </p>
    <p class="meta">Créé par : <?php echo e($committee->creator->name ?? 'N/A'); ?></p>
    <p class="meta">Statut :
        <?php if($committee->is_active): ?>
            <span class="badge badge-success">Actif</span>
        <?php else: ?>
            <span class="badge badge-secondary">Inactif</span>
        <?php endif; ?>
    </p>

    <h2>Membres (<?php echo e($committee->members->count()); ?>)</h2>
    <?php if($committee->members->isNotEmpty()): ?>
        <table>
            <thead>
                <tr>
                    <th>Participant</th>
                    <th>Rôle</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $committee->members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($member->user->name ?? 'N/A'); ?></td>
                        <td><span class="badge badge-primary"><?php echo e($member->role); ?></span></td>
                        <td><?php echo e($member->notes ?? '—'); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="meta">Aucun membre enregistré.</p>
    <?php endif; ?>

    <div class="footer">
        BP:2112 Libreville-GABON Tel. +(241) 44 47 31, +(241) 44 47 34 -Email : commission@ceeac-eccas.org
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\organization-committees\pdf.blade.php ENDPATH**/ ?>