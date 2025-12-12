
<?php
    $logoPath = public_path('images/logo-ceeac.png');
    if (!file_exists($logoPath)) {
        $alternatives = glob(public_path('images/*ceeac*.png'));
        $logoPath = $alternatives[0] ?? null;
    }
?>

<div class="pdf-header">
    <table class="pdf-header-table">
        <tr>
            <td style="width: 80px;">
                <?php if($logoPath && file_exists($logoPath)): ?>
                    <img src="<?php echo e($logoPath); ?>" alt="CEEAC" class="pdf-logo">
                <?php endif; ?>
            </td>
            <td style="width: 20px;"></td>
            <td>
                <div style="font-size: 11px; color: #1e3a8a; font-weight: bold;">
                    COMMUNAUTÉ ÉCONOMIQUE DES ÉTATS DE L'AFRIQUE CENTRALE
                </div>
                <div style="font-size: 10px; color: #6b7280; margin-top: 2px;">
                    ECONOMIC COMMUNITY OF CENTRAL AFRICAN STATES
                </div>
            </td>
            <td class="pdf-header-title">
                <div>SGRS-CEEAC</div>
                <div class="pdf-header-subtitle">
                    Système de Gestion des Réunions Statutaires
                </div>
            </td>
        </tr>
    </table>
</div>












<?php /**PATH C:\laragon\www\sgrs-alpha\resources\views/pdf/partials/header.blade.php ENDPATH**/ ?>