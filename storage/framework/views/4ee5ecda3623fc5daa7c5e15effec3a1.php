<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php echo $__env->yieldContent('title', 'Document SGRS-CEEAC'); ?></title>
    <style>
        /* ========================================
           STYLES PDF SGRS-CEEAC - LAYOUT MASTER
           ======================================== */
        
        @page {
            margin: 120px 30px 80px 30px;
        }
        
        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #1f2937;
            margin: 0;
            padding: 0 20px;
            box-sizing: border-box;
        }
        
        /* === HEADER INSTITUTIONNEL === */
        .pdf-header {
            position: fixed;
            top: -100px;
            left: 0;
            right: 0;
            height: 90px;
            border-bottom: 3px solid #1e3a8a;
            padding-bottom: 10px;
        }
        
        .pdf-header-table {
            width: 100%;
            border: none;
        }
        
        .pdf-header-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }
        
        .pdf-logo {
            height: 55px;
            width: auto;
        }
        
        .pdf-header-title {
            text-align: right;
            font-size: 12px;
            color: #1e3a8a;
            font-weight: bold;
            line-height: 1.3;
        }
        
        .pdf-header-subtitle {
            font-size: 10px;
            color: #6b7280;
            font-weight: normal;
        }
        
        /* === FOOTER INSTITUTIONNEL === */
        .pdf-footer {
            position: fixed;
            bottom: -60px;
            left: 0;
            right: 0;
            height: 50px;
            border-top: 2px solid #1e3a8a;
            padding-top: 8px;
            font-size: 9px;
            color: #6b7280;
            text-align: center;
        }
        
        .pdf-footer-address {
            margin-bottom: 3px;
        }
        
        .pdf-footer-generated {
            color: #9ca3af;
            font-size: 8px;
        }
        
        .page-number:after {
            content: counter(page);
        }
        
        /* === TITRES === */
        h1 {
            font-size: 20px;
            color: #1e3a8a;
            margin: 0 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #e5e7eb;
        }
        
        h2 {
            font-size: 15px;
            color: #1e40af;
            margin: 20px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        h3 {
            font-size: 13px;
            color: #374151;
            margin: 15px 0 8px 0;
        }
        
        h4 {
            font-size: 12px;
            color: #4b5563;
            margin: 12px 0 6px 0;
        }
        
        /* === PARAGRAPHES === */
        p {
            margin: 4px 0 8px 0;
        }
        
        .text-muted {
            color: #6b7280;
        }
        
        .text-small {
            font-size: 10px;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-justify {
            text-align: justify;
        }
        
        /* === BADGES === */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .badge-primary {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .badge-success {
            background: #dcfce7;
            color: #166534;
        }
        
        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }
        
        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .badge-info {
            background: #e0f2fe;
            color: #075985;
        }
        
        .badge-secondary {
            background: #f3f4f6;
            color: #4b5563;
        }
        
        /* === TABLEAUX === */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        
        th, td {
            border: 1px solid #e5e7eb;
            padding: 8px 10px;
            text-align: left;
            font-size: 10px;
        }
        
        th {
            background: #f8fafc;
            font-weight: bold;
            color: #374151;
        }
        
        tr:nth-child(even) {
            background: #fafafa;
        }
        
        /* === GRILLES === */
        .info-grid {
            display: table;
            width: 100%;
            margin: 10px 0;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            width: 35%;
            padding: 4px 8px;
            font-weight: bold;
            color: #374151;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
        }
        
        .info-value {
            display: table-cell;
            padding: 4px 8px;
            border: 1px solid #e5e7eb;
        }
        
        /* === SECTIONS === */
        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        
        .section-title {
            background: #1e3a8a;
            color: #ffffff;
            padding: 8px 12px;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .subsection {
            margin: 10px 0;
            padding: 10px;
            background: #f9fafb;
            border-left: 3px solid #1e3a8a;
        }
        
        /* === BOÎTES === */
        .info-box {
            padding: 12px;
            margin: 10px 0;
            border-radius: 4px;
        }
        
        .info-box-primary {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
        }
        
        .info-box-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
        }
        
        .info-box-warning {
            background: #fffbeb;
            border: 1px solid #fde68a;
        }
        
        .info-box-danger {
            background: #fef2f2;
            border: 1px solid #fecaca;
        }
        
        /* === SIGNATURES === */
        .signature-section {
            margin-top: 40px;
            page-break-inside: avoid;
        }
        
        .signature-box {
            display: inline-block;
            width: 45%;
            margin: 10px 2%;
            vertical-align: top;
            text-align: center;
        }
        
        .signature-line {
            border-top: 2px solid #374151;
            margin-top: 50px;
            padding-top: 8px;
        }
        
        /* === PAGE BREAKS === */
        .page-break {
            page-break-after: always;
        }
        
        .avoid-break {
            page-break-inside: avoid;
        }
        
        /* === LISTES === */
        ul, ol {
            margin: 8px 0;
            padding-left: 25px;
        }
        
        li {
            margin-bottom: 4px;
        }
        
        /* === MARGES === */
        .mt-0 { margin-top: 0; }
        .mt-1 { margin-top: 5px; }
        .mt-2 { margin-top: 10px; }
        .mt-3 { margin-top: 15px; }
        .mt-4 { margin-top: 20px; }
        
        .mb-0 { margin-bottom: 0; }
        .mb-1 { margin-bottom: 5px; }
        .mb-2 { margin-bottom: 10px; }
        .mb-3 { margin-bottom: 15px; }
        .mb-4 { margin-bottom: 20px; }
        
        <?php echo $__env->yieldContent('styles'); ?>
    </style>
</head>
<body>
    
    <?php echo $__env->make('pdf.partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    
    <?php echo $__env->make('pdf.partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    
    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>
</body>
</html>



<?php /**PATH C:\laragon\www\sgrs-alpha\resources\views/pdf/layouts/master.blade.php ENDPATH**/ ?>