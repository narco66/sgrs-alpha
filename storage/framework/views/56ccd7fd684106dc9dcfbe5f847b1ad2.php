<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rapport <?php echo e(ucfirst($reportType)); ?> - SGRS-CEEAC</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #667eea;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #667eea;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .summary {
            background-color: #f0f4ff;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Commission de la CEEAC</h1>
        <p>Système de Gestion des Réunions Statutaires (SGRS-CEEAC)</p>
        <h2>Rapport : <?php echo e(ucfirst($reportType)); ?></h2>
        <p>Période : <?php echo e(\Carbon\Carbon::parse($startDate)->format('d/m/Y')); ?> - <?php echo e(\Carbon\Carbon::parse($endDate)->format('d/m/Y')); ?></p>
        <p>Généré le : <?php echo e(now()->format('d/m/Y à H:i')); ?></p>
    </div>

    <?php if($reportType === 'meetings'): ?>
        <?php if(isset($data['byType']) && $data['byType']->count() > 0): ?>
            <h3>Réunions par Type</h3>
            <table>
                <thead>
                    <tr>
                        <th>Type de réunion</th>
                        <th>Nombre</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $data['byType']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($item->type ?? 'Non défini'); ?></td>
                            <td><?php echo e($item->total); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php endif; ?>

        <?php if(isset($data['byStatus']) && $data['byStatus']->count() > 0): ?>
            <h3>Réunions par Statut</h3>
            <table>
                <thead>
                    <tr>
                        <th>Statut</th>
                        <th>Nombre</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $data['byStatus']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e(ucfirst($item->status)); ?></td>
                            <td><?php echo e($item->total); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php endif; ?>

    <?php elseif($reportType === 'participants'): ?>
        <?php if(isset($data['byService']) && $data['byService']->count() > 0): ?>
            <h3>Participation par Service</h3>
            <table>
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Invitations</th>
                        <th>Confirmés</th>
                        <th>Taux de participation</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $data['byService']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $rate = $item->total_invitations > 0 
                                ? round(($item->confirmed / $item->total_invitations) * 100, 2) 
                                : 0;
                        ?>
                        <tr>
                            <td><?php echo e($item->service); ?></td>
                            <td><?php echo e($item->total_invitations); ?></td>
                            <td><?php echo e($item->confirmed); ?></td>
                            <td><?php echo e($rate); ?>%</td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php endif; ?>

    <?php elseif($reportType === 'documents'): ?>
        <?php if(isset($data['byType']) && $data['byType']->count() > 0): ?>
            <h3>Documents par Type</h3>
            <table>
                <thead>
                    <tr>
                        <th>Type de document</th>
                        <th>Nombre</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $data['byType']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($item->document_type ?? 'Non défini'); ?></td>
                            <td><?php echo e($item->total); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>

    <div class="footer">
        <p>Document généré automatiquement par le Système de Gestion des Réunions Statutaires de la CEEAC</p>
        <p>Commission de la CEEAC - <?php echo e(now()->year); ?></p>
    </div>
</body>
</html>

<?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\reports\exports\pdf.blade.php ENDPATH**/ ?>