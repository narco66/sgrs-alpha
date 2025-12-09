<?php $__env->startSection('title', 'Rapport ' . ucfirst($reportType) . ' - SGRS-CEEAC'); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .report-header {
        text-align: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #1e3a8a;
    }
    .report-title {
        font-size: 20px;
        color: #1e3a8a;
        margin: 0 0 10px 0;
        text-transform: uppercase;
    }
    .report-period {
        font-size: 12px;
        color: #6b7280;
    }
    .summary-box {
        background: #eff6ff;
        padding: 15px;
        border-radius: 4px;
        margin: 15px 0;
        border-left: 4px solid #1e3a8a;
    }
    .chart-placeholder {
        background: #f9fafb;
        padding: 20px;
        text-align: center;
        color: #6b7280;
        border: 1px dashed #e5e7eb;
        margin: 10px 0;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="report-header">
    <div class="report-title">
        Rapport : <?php echo e(ucfirst($reportType)); ?>

    </div>
    <div class="report-period">
        Période : <?php echo e(\Carbon\Carbon::parse($startDate)->format('d/m/Y')); ?> - <?php echo e(\Carbon\Carbon::parse($endDate)->format('d/m/Y')); ?>

    </div>
</div>


<?php if($reportType === 'meetings'): ?>
    
    <?php if(isset($data['total'])): ?>
    <div class="summary-box">
        <h3 style="margin: 0 0 10px 0;">Résumé</h3>
        <p><strong>Total des réunions :</strong> <?php echo e($data['total'] ?? 0); ?></p>
    </div>
    <?php endif; ?>
    
    
    <?php if(isset($data['byType']) && $data['byType']->count() > 0): ?>
    <div class="section">
        <h2>Réunions par Type</h2>
        <table>
            <thead>
                <tr>
                    <th>Type de réunion</th>
                    <th style="width: 100px; text-align: center;">Nombre</th>
                    <th style="width: 100px; text-align: center;">Pourcentage</th>
                </tr>
            </thead>
            <tbody>
                <?php $totalByType = $data['byType']->sum('total'); ?>
                <?php $__currentLoopData = $data['byType']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($item->type ?? 'Non défini'); ?></td>
                        <td style="text-align: center;"><?php echo e($item->total); ?></td>
                        <td style="text-align: center;">
                            <?php echo e($totalByType > 0 ? round(($item->total / $totalByType) * 100, 1) : 0); ?>%
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
    
    
    <?php if(isset($data['byStatus']) && $data['byStatus']->count() > 0): ?>
    <div class="section">
        <h2>Réunions par Statut</h2>
        <table>
            <thead>
                <tr>
                    <th>Statut</th>
                    <th style="width: 100px; text-align: center;">Nombre</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $statusLabels = [
                        'brouillon' => 'Brouillon',
                        'planifiee' => 'Planifiée',
                        'en_preparation' => 'En préparation',
                        'en_cours' => 'En cours',
                        'terminee' => 'Terminée',
                        'annulee' => 'Annulée'
                    ];
                ?>
                <?php $__currentLoopData = $data['byStatus']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($statusLabels[$item->status] ?? ucfirst($item->status)); ?></td>
                        <td style="text-align: center;"><?php echo e($item->total); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
    
    
    <?php if(isset($data['byMonth']) && $data['byMonth']->count() > 0): ?>
    <div class="section">
        <h2>Évolution mensuelle</h2>
        <table>
            <thead>
                <tr>
                    <th>Mois</th>
                    <th style="width: 100px; text-align: center;">Nombre</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $data['byMonth']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($item->month ?? $item->period ?? 'N/A'); ?></td>
                        <td style="text-align: center;"><?php echo e($item->total); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>


<?php elseif($reportType === 'participants'): ?>
    
    <?php if(isset($data['byService']) && $data['byService']->count() > 0): ?>
    <div class="section">
        <h2>Participation par Service</h2>
        <table>
            <thead>
                <tr>
                    <th>Service</th>
                    <th style="text-align: center;">Invitations</th>
                    <th style="text-align: center;">Confirmés</th>
                    <th style="text-align: center;">Taux</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $data['byService']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $rate = $item->total_invitations > 0 
                            ? round(($item->confirmed / $item->total_invitations) * 100, 1) 
                            : 0;
                    ?>
                    <tr>
                        <td><?php echo e($item->service ?? 'Non défini'); ?></td>
                        <td style="text-align: center;"><?php echo e($item->total_invitations); ?></td>
                        <td style="text-align: center;"><?php echo e($item->confirmed); ?></td>
                        <td style="text-align: center;">
                            <span class="badge <?php echo e($rate >= 75 ? 'badge-success' : ($rate >= 50 ? 'badge-warning' : 'badge-danger')); ?>">
                                <?php echo e($rate); ?>%
                            </span>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
    
    
    <?php if(isset($data['responseRate'])): ?>
    <div class="summary-box">
        <h3>Taux de réponse global</h3>
        <p style="font-size: 24px; color: #1e3a8a; margin: 10px 0;">
            <?php echo e($data['responseRate'] ?? 0); ?>%
        </p>
    </div>
    <?php endif; ?>


<?php elseif($reportType === 'documents'): ?>
    
    <?php if(isset($data['byType']) && $data['byType']->count() > 0): ?>
    <div class="section">
        <h2>Documents par Type</h2>
        <table>
            <thead>
                <tr>
                    <th>Type de document</th>
                    <th style="width: 100px; text-align: center;">Nombre</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $data['byType']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($item->document_type ?? $item->type ?? 'Non défini'); ?></td>
                        <td style="text-align: center;"><?php echo e($item->total); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
    
    
    <?php if(isset($data['byMeeting']) && $data['byMeeting']->count() > 0): ?>
    <div class="section">
        <h2>Documents par Réunion</h2>
        <table>
            <thead>
                <tr>
                    <th>Réunion</th>
                    <th style="width: 100px; text-align: center;">Documents</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $data['byMeeting']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($item->meeting ?? 'Non défini'); ?></td>
                        <td style="text-align: center;"><?php echo e($item->total); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>


<?php elseif($reportType === 'performance'): ?>
    <div class="summary-box">
        <h3>Indicateurs de Performance</h3>
        <?php if(isset($data['metrics'])): ?>
            <table style="margin-top: 10px;">
                <?php $__currentLoopData = $data['metrics']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $metric => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td style="width: 60%; font-weight: bold;"><?php echo e($metric); ?></td>
                        <td><?php echo e($value); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </table>
        <?php else: ?>
            <p class="text-muted">Aucune donnée de performance disponible.</p>
        <?php endif; ?>
    </div>
<?php endif; ?>


<div class="section mt-4">
    <div class="info-box info-box-warning">
        <p class="text-small">
            <strong>Note :</strong> Ce rapport a été généré automatiquement par le système SGRS-CEEAC.
            Les données présentées reflètent l'état de la base de données au moment de la génération.
        </p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('pdf.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views/reports/exports/pdf.blade.php ENDPATH**/ ?>