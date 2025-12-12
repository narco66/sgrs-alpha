<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Badges - <?php echo e($delegation->title ?? 'Délégation'); ?></title>
    <style>
        /**
         * Template de badges multiples pour SGRS-CEEAC
         * Format: 85mm x 54mm (format carte de crédit)
         * Encodage: UTF-8 pour les accents français
         */
        
        /* Configuration de la page pour DomPDF */
        @page {
            margin: 0;
            padding: 0;
            size: 85mm 54mm landscape;
        }
        
        /* Reset complet */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            margin: 0;
            padding: 0;
            width: 85mm;
            height: 54mm;
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
        }
        
        /* Container de badge - une page par badge */
        .badge-container {
            width: 85mm;
            height: 54mm;
            position: relative;
            overflow: hidden;
            background: #ffffff;
            page-break-after: always;
            page-break-inside: avoid;
        }
        
        /* Dernier badge sans saut de page */
        .badge-container.last-badge {
            page-break-after: avoid;
        }
        
        /* ========================================
           HEADER DU BADGE
           ======================================== */
        .badge-header {
            width: 100%;
            height: 14mm;
            background-color: #1e3a8a;
            text-align: center;
            padding-top: 2.5mm;
            position: absolute;
            top: 0;
            left: 0;
        }
        
        /* Couleurs selon le type */
        .badge-header.type-head {
            background: linear-gradient(135deg, #991b1b 0%, #7f1d1d 100%);
        }
        
        .badge-header.type-vip {
            background: linear-gradient(135deg, #854d0e 0%, #713f12 100%);
        }
        
        .badge-header.type-staff {
            background: linear-gradient(135deg, #166534 0%, #14532d 100%);
        }
        
        .badge-header.type-member {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
        }
        
        .badge-logo {
            height: 6mm;
            vertical-align: middle;
        }
        
        .badge-org-title {
            display: block;
            color: #ffffff;
            font-size: 6.5pt;
            font-weight: bold;
            letter-spacing: 0.3px;
            margin-top: 0.8mm;
            text-transform: uppercase;
        }
        
        /* ========================================
           CORPS DU BADGE
           ======================================== */
        .badge-body {
            width: 100%;
            position: absolute;
            top: 14mm;
            left: 0;
            height: 34mm;
            text-align: center;
            padding: 0.5mm 3mm 1mm 3mm;
            overflow: hidden;
        }
        
        .participant-name {
            font-size: 11pt;
            font-weight: bold;
            color: #1e293b;
            line-height: 1.15;
            margin-bottom: 0.5mm;
            max-height: 7mm;
            overflow: hidden;
        }
        
        /* Couleur du nom selon le type */
        .type-head .participant-name {
            color: #991b1b;
        }
        
        .type-vip .participant-name {
            color: #854d0e;
        }
        
        .participant-title {
            font-size: 7pt;
            color: #64748b;
            margin-bottom: 0.5mm;
            max-height: 3.5mm;
            overflow: hidden;
        }
        
        .participant-organization {
            font-size: 8pt;
            color: #334155;
            font-weight: 600;
            margin-bottom: 1mm;
            max-height: 4mm;
            overflow: hidden;
        }
        
        /* Rôle/Fonction dans un badge coloré */
        .badge-role {
            display: inline-block;
            font-size: 6.5pt;
            padding: 0.8mm 2.5mm;
            border-radius: 1.5mm;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.2px;
        }
        
        .type-head .badge-role {
            background-color: #fecaca;
            color: #991b1b;
        }
        
        .type-vip .badge-role {
            background-color: #fef3c7;
            color: #854d0e;
        }
        
        .type-staff .badge-role {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .type-member .badge-role {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        /* Informations sur la réunion */
        .meeting-info {
            font-size: 6pt;
            color: #1e293b;
            font-weight: bold;
            margin-top: 0.8mm;
            padding-top: 0.8mm;
            border-top: 0.2mm dashed #cbd5e1;
            line-height: 1.3;
            max-height: 8mm;
            overflow: hidden;
        }
        
        /* ========================================
           FOOTER DU BADGE
           ======================================== */
        .badge-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 6mm;
            background-color: #f1f5f9;
            text-align: center;
            padding-top: 1.5mm;
            font-size: 5pt;
            color: #64748b;
            border-top: 0.2mm solid #e2e8f0;
        }
    </style>
</head>
<body>
<?php
    // Chemin du logo CEEAC
    $logoPath = public_path('images/logo-ceeac.png');
    if (!file_exists($logoPath)) {
        $alternatives = glob(public_path('images/*ceeac*.png'));
        $logoPath = !empty($alternatives) ? $alternatives[0] : null;
    }
    
    // Labels des rôles en français
    $roleLabels = [
        'head' => 'CHEF DE DÉLÉGATION',
        'deputy' => 'CHEF ADJOINT',
        'member' => 'MEMBRE',
        'advisor' => 'CONSEILLER',
        'expert' => 'EXPERT',
        'observer' => 'OBSERVATEUR',
        'interpreter' => 'INTERPRÈTE',
        'secretary' => 'SECRÉTAIRE',
        'staff' => 'PERSONNEL',
        'organizer' => 'ORGANISATEUR',
        'vip' => 'VIP',
        'minister' => 'MINISTRE',
        'ambassador' => 'AMBASSADEUR',
    ];
    
    // Nom de la délégation pour l'affichage
    $organizationName = $delegation->title ?? $delegation->country ?? $delegation->organization_name ?? '';
    
    // Fonction pour déterminer le type de badge selon le rôle
    $getBadgeType = function($role) {
        $role = strtolower($role ?? 'member');
        if (in_array($role, ['head', 'president', 'chairman', 'chef'])) {
            return 'head';
        } elseif (in_array($role, ['vip', 'minister', 'ambassador', 'ministre', 'ambassadeur'])) {
            return 'vip';
        } elseif (in_array($role, ['staff', 'organizer', 'secretariat', 'personnel'])) {
            return 'staff';
        }
        return 'member';
    };
    
    // Nombre total de participants
    $totalParticipants = count($participants);
?>


<?php $__currentLoopData = $participants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $participant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php
    $role = $participant->role ?? 'member';
    $badgeType = $getBadgeType($role);
    $isLastBadge = ($index === $totalParticipants - 1);
    
    // Construire le nom complet
    $fullName = '';
    if (!empty($participant->title)) {
        $fullName .= $participant->title . ' ';
    }
    if (!empty($participant->first_name)) {
        $fullName .= $participant->first_name . ' ';
    }
    if (!empty($participant->last_name)) {
        $fullName .= $participant->last_name;
    }
    $fullName = trim($fullName);
    
    // Fallback sur full_name ou name
    if (empty($fullName)) {
        $fullName = $participant->full_name ?? $participant->name ?? 'Participant';
    }
    
    // Position/Fonction
    $position = $participant->position ?? '';
?>
<div class="badge-container type-<?php echo e($badgeType); ?> <?php echo e($isLastBadge ? 'last-badge' : ''); ?>">
    
    <div class="badge-header type-<?php echo e($badgeType); ?>">
        <?php if($logoPath && file_exists($logoPath)): ?>
            <img src="<?php echo e($logoPath); ?>" alt="CEEAC" class="badge-logo">
        <?php endif; ?>
        <span class="badge-org-title">CEEAC – Réunion Statutaire</span>
    </div>
    
    
    <div class="badge-body">
        <div class="participant-name"><?php echo e($fullName); ?></div>
        
        <?php if(!empty($position)): ?>
            <div class="participant-title"><?php echo e($position); ?></div>
        <?php endif; ?>
        
        <?php if(!empty($organizationName)): ?>
            <div class="participant-organization"><?php echo e($organizationName); ?></div>
        <?php endif; ?>
        
        <div class="badge-role">
            <?php echo e($roleLabels[$role] ?? strtoupper(str_replace('_', ' ', $role))); ?>

        </div>
        
        <?php if(isset($meeting) && $meeting): ?>
            <div class="meeting-info">
                <?php echo e(\Illuminate\Support\Str::limit($meeting->title ?? '', 80)); ?>

                <?php if($meeting->start_at): ?>
                    <br><?php echo e($meeting->start_at->format('d/m/Y')); ?>

                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    
    
    <div class="badge-footer">
        SGRS-CEEAC • Ce badge doit être porté de manière visible
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</body>
</html>
<?php /**PATH C:\laragon\www\sgrs-alpha\resources\views/participants/pdf-badges-multiple.blade.php ENDPATH**/ ?>