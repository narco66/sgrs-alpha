

<?php $__env->startSection('title', 'Note logistique - ' . $meeting->title); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .logistics-header {
        text-align: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #1e3a8a;
    }
    .logistics-title {
        font-size: 18px;
        color: #1e3a8a;
        text-transform: uppercase;
        margin: 10px 0;
    }
    .info-card {
        background: #f9fafb;
        padding: 15px;
        margin: 15px 0;
        border-left: 4px solid #1e3a8a;
        page-break-inside: avoid;
    }
    .info-card-title {
        font-weight: bold;
        color: #1e3a8a;
        margin-bottom: 10px;
        font-size: 13px;
    }
    .contact-box {
        background: #eff6ff;
        padding: 12px;
        border-radius: 4px;
        margin: 10px 0;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="logistics-header">
    <div class="logistics-title">NOTE D'INFORMATION LOGISTIQUE</div>
    <p class="text-muted"><?php echo e($meeting->title); ?></p>
    <p class="text-small text-muted">
        Réf: CEEAC/LOG/<?php echo e(date('Y')); ?>/<?php echo e(str_pad($meeting->id, 4, '0', STR_PAD_LEFT)); ?>

    </p>
</div>


<div class="section">
    <p class="text-justify">
        La présente note a pour objet de fournir aux participants les informations pratiques 
        relatives à l'organisation de la réunion « <strong><?php echo e($meeting->title); ?></strong> ».
    </p>
</div>


<div class="info-card">
    <div class="info-card-title">1. DATE ET LIEU</div>
    <table style="width: 100%; border: none;">
        <tr>
            <td style="width: 30%; font-weight: bold; border: none; padding: 4px 0;">Date :</td>
            <td style="border: none;"><?php echo e($meeting->start_at?->format('l d F Y') ?? 'À confirmer'); ?></td>
        </tr>
        <tr>
            <td style="font-weight: bold; border: none; padding: 4px 0;">Horaires :</td>
            <td style="border: none;">
                <?php echo e($meeting->start_at?->format('H:i') ?? '—'); ?> - <?php echo e($meeting->end_at?->format('H:i') ?? '—'); ?>

            </td>
        </tr>
        <tr>
            <td style="font-weight: bold; border: none; padding: 4px 0;">Lieu :</td>
            <td style="border: none;">
                <?php if($meeting->room): ?>
                    <?php echo e($meeting->room->name); ?>

                    <?php if($meeting->room->building): ?>
                        <br>Bâtiment : <?php echo e($meeting->room->building); ?>

                    <?php endif; ?>
                    <?php if($meeting->room->address): ?>
                        <br><?php echo e($meeting->room->address); ?>

                    <?php endif; ?>
                <?php else: ?>
                    Siège de la Commission de la CEEAC
                    <br>BP 2112, Libreville, GABON
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold; border: none; padding: 4px 0;">Configuration :</td>
            <td style="border: none;">
                <?php
                    $configs = [
                        'presentiel' => 'Réunion en présentiel',
                        'hybride' => 'Réunion hybride (présentiel et visioconférence)',
                        'visioconference' => 'Réunion en visioconférence uniquement'
                    ];
                ?>
                <?php echo e($configs[$meeting->configuration ?? 'presentiel'] ?? 'En présentiel'); ?>

            </td>
        </tr>
    </table>
</div>


<div class="info-card">
    <div class="info-card-title">2. INSCRIPTION ET ACCRÉDITATION</div>
    <p>
        Les délégations sont priées de communiquer la liste de leurs participants au plus tard 
        <strong><?php echo e($meeting->start_at?->subDays(7)->format('d/m/Y') ?? '7 jours avant la réunion'); ?></strong>.
    </p>
    <p>
        L'accréditation des participants se fera sur place le jour de la réunion, 
        30 minutes avant le début de la séance. Chaque participant devra présenter une pièce d'identité valide.
    </p>
</div>


<div class="info-card">
    <div class="info-card-title">3. DOCUMENTS DE TRAVAIL</div>
    <p>
        Les documents de travail seront mis à la disposition des participants :
    </p>
    <ul>
        <li>En version électronique via la plateforme SGRS-CEEAC</li>
        <li>En version papier le jour de la réunion (nombre limité)</li>
    </ul>
    
    <?php $documents = $meeting->documents ?? collect(); ?>
    <?php if($documents->count()): ?>
        <p style="margin-top: 10px;"><strong>Documents disponibles :</strong></p>
        <table>
            <thead>
                <tr>
                    <th>Document</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($doc->title); ?></td>
                        <td><?php echo e($doc->type?->name ?? '—'); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>


<div class="info-card">
    <div class="info-card-title">4. LANGUES DE TRAVAIL</div>
    <p>
        Les langues de travail de la réunion sont le <strong>français</strong> et le <strong>portugais</strong>.
        Une interprétation simultanée sera assurée si nécessaire.
    </p>
</div>


<?php if($meeting->termsOfReference || ($meeting->configuration ?? 'presentiel') === 'presentiel'): ?>
<div class="info-card">
    <div class="info-card-title">5. HÉBERGEMENT</div>
    <p>
        <?php if($meeting->termsOfReference && $meeting->termsOfReference->host_country): ?>
            Conformément au cahier des charges établi avec le <?php echo e($meeting->termsOfReference->host_country); ?>,
            les modalités d'hébergement sont les suivantes :
        <?php else: ?>
            Les participants sont invités à prendre leurs propres dispositions en matière d'hébergement.
            Voici quelques hôtels recommandés à Libreville :
        <?php endif; ?>
    </p>
    <ul>
        <li>Radisson Blu Okoumé Palace - Tél: +241 XX XX XX XX</li>
        <li>Hôtel Nomad - Tél: +241 XX XX XX XX</li>
        <li>Park Inn by Radisson - Tél: +241 XX XX XX XX</li>
    </ul>
    <p class="text-small text-muted">
        <em>Note : Un tarif préférentiel peut être négocié. Contactez le secrétariat pour plus d'informations.</em>
    </p>
</div>
<?php endif; ?>


<div class="info-card">
    <div class="info-card-title">6. TRANSPORT</div>
    <p>
        <strong>Aéroport international Léon Mba (LBV)</strong> - Libreville, Gabon
    </p>
    <p>
        Les participants sont priés de communiquer leurs informations de vol pour permettre 
        l'organisation de leur accueil à l'aéroport.
    </p>
    <p>
        Un service de navette pourra être organisé entre les hôtels et le lieu de la réunion.
    </p>
</div>


<div class="info-card">
    <div class="info-card-title">7. CONTACTS</div>
    
    <div class="contact-box">
        <p><strong>Secrétariat de la réunion</strong></p>
        <p>Commission de la CEEAC</p>
        <p>Email : commission@ceeac-eccas.org</p>
        <p>Tél : +(241) 44 47 31 / 44 47 34</p>
    </div>
    
    <?php if($meeting->organizer): ?>
    <div class="contact-box">
        <p><strong>Organisateur principal</strong></p>
        <p><?php echo e($meeting->organizer->name); ?></p>
        <?php if($meeting->organizer->email): ?>
            <p>Email : <?php echo e($meeting->organizer->email); ?></p>
        <?php endif; ?>
        <?php if($meeting->organizer->phone): ?>
            <p>Tél : <?php echo e($meeting->organizer->phone); ?></p>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>


<div class="info-card">
    <div class="info-card-title">8. NUMÉROS D'URGENCE</div>
    <table style="width: 100%; border: none;">
        <tr>
            <td style="width: 50%; border: none; padding: 4px 0;">Police : 177</td>
            <td style="border: none; padding: 4px 0;">Pompiers : 18</td>
        </tr>
        <tr>
            <td style="border: none; padding: 4px 0;">SAMU : 1300</td>
            <td style="border: none; padding: 4px 0;">Urgences médicales : +241 XX XX XX XX</td>
        </tr>
    </table>
</div>


<div class="section mt-3">
    <div class="info-box info-box-warning">
        <p class="text-small">
            <strong>Important :</strong> Pour toute modification ou annulation de participation, 
            veuillez en informer le secrétariat au moins 48 heures à l'avance.
        </p>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('pdf.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views/meetings/pdf-logistics.blade.php ENDPATH**/ ?>