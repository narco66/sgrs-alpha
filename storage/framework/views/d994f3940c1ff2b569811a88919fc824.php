<?php $__env->startSection('title', 'Documents'); ?>

<?php $__env->startSection('content'); ?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Accueil</a></li>
        <li class="breadcrumb-item active">Documents</li>
    </ol>
</nav>


<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="page-title mb-1">Documents</h3>
        <p class="text-muted mb-0 small">Accueil / Documents</p>
    </div>
</div>

<?php echo $__env->make('partials.alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\Document::class)): ?>
<div class="modern-card mb-4">
    <div class="modern-card-body">
        <form action="<?php echo e(route('documents.store')); ?>" method="POST" enctype="multipart/form-data" id="documentUploadForm">
            <?php echo csrf_field(); ?>
            <div class="document-upload-zone" id="uploadZone">
                <div class="upload-zone-content">
                    <i class="bi bi-cloud-upload upload-icon"></i>
                    <h5 class="upload-title">Déposer le document ici</h5>
                    <p class="upload-subtitle">ou</p>
                    <label for="fileInput" class="btn btn-modern btn-modern-primary">
                        <i class="bi bi-folder2-open"></i> Parcourir les fichiers
                    </label>
                    <input type="file" 
                           name="file" 
                           id="fileInput" 
                           class="d-none" 
                           accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
                           required>
                </div>
                <div class="upload-info mt-3">
                    <p class="small text-muted mb-1">
                        <i class="bi bi-info-circle me-1"></i>
                        <strong>Taille max:</strong> 500 Mo
                    </p>
                    <p class="small text-muted mb-0">
                        <i class="bi bi-file-earmark me-1"></i>
                        <strong>Formats acceptés:</strong> PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX
                    </p>
                </div>
            </div>
            
            <div id="fileInfo" class="d-none mt-3">
                <div class="alert-modern alert-modern-info">
                    <i class="bi bi-file-earmark-check"></i>
                    <div>
                        <strong id="fileName"></strong>
                        <div class="small" id="fileSize"></div>
                    </div>
                </div>
            </div>
            
            <div class="row g-3 mt-3 d-none" id="documentDetails">
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="bi bi-file-text"></i>
                        Titre du document
                        <span class="required">*</span>
                    </label>
                    <input type="text" 
                           name="title" 
                           class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           placeholder="Ex: Procès-verbal du Conseil des Ministres"
                           required>
                    <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback">
                            <i class="bi bi-exclamation-circle"></i>
                            <?php echo e($message); ?>

                        </div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="bi bi-diagram-3"></i>
                        Type de document
                    </label>
                    <select name="document_type_id" class="form-select <?php $__errorArgs = ['document_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="">Sélectionner un type (optionnel)</option>
                        <?php $__currentLoopData = $documentTypes ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $docType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($docType->id); ?>"><?php echo e($docType->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['document_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback">
                            <i class="bi bi-exclamation-circle"></i>
                            <?php echo e($message); ?>

                        </div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-12">
                    <label class="form-label">
                        <i class="bi bi-calendar-event"></i>
                        Réunion associée (optionnel)
                    </label>
                    <select name="meeting_id" class="form-select <?php $__errorArgs = ['meeting_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="">Aucune réunion</option>
                        <?php $__currentLoopData = $meetings ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meeting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($meeting->id); ?>"><?php echo e($meeting->title); ?> - <?php echo e($meeting->start_at?->format('d/m/Y')); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['meeting_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback">
                            <i class="bi bi-exclamation-circle"></i>
                            <?php echo e($message); ?>

                        </div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-12">
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-modern btn-modern-secondary" onclick="resetUpload()">
                            <i class="bi bi-x-circle"></i> Annuler
                        </button>
                        <button type="submit" class="btn btn-modern btn-modern-primary">
                            <i class="bi bi-upload"></i> Téléverser le document
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>


<div class="modern-filters mb-4" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-radius: 20px; padding: 2rem; border: 2px solid #f1f5f9; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);">
    <div class="row g-3 align-items-end">
        <div class="col-md-6">
            <label class="form-label fw-bold" style="color: #1e293b; margin-bottom: 0.75rem;">
                <i class="bi bi-search text-primary"></i>
                Rechercher un document
            </label>
            <form method="GET" action="<?php echo e(route('documents.index')); ?>" class="d-flex gap-2">
                <div class="flex-grow-1 position-relative">
                    <input type="text" 
                           name="q" 
                           class="form-control" 
                           value="<?php echo e($search ?? ''); ?>" 
                           placeholder="Rechercher un document..."
                           style="border-radius: 12px; border: 2px solid #e2e8f0; padding: 0.75rem 1rem; transition: all 0.3s;"
                           onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'"
                           onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                </div>
                <button type="submit" class="btn btn-modern btn-modern-primary" style="border-radius: 12px; padding: 0.75rem 1.5rem;">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
        <div class="col-md-6">
            <div class="d-flex justify-content-end gap-2 align-items-end">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewAny', \App\Models\DocumentType::class)): ?>
                <a href="<?php echo e(route('document-types.index')); ?>" class="btn btn-modern btn-modern-secondary" style="border-radius: 12px;">
                    <i class="bi bi-gear"></i> Gérer les types de documents
                </a>
                <?php endif; ?>
                <button type="button" class="btn btn-modern btn-modern-primary" id="downloadSelectedBtn" style="display: none; border-radius: 12px;">
                    <i class="bi bi-download"></i> Télécharger
                </button>
            </div>
        </div>
    </div>
    
    
    <div class="mt-4 pt-3" style="border-top: 2px solid #f1f5f9;">
        <label class="form-label mb-3 fw-bold" style="color: #1e293b;">
            <i class="bi bi-funnel text-primary"></i>
            Filtrer par type
        </label>
        <div class="document-type-filters">
            <a href="<?php echo e(route('documents.index', array_merge(request()->except(['ext', 'page']), ['ext' => 'all']))); ?>" 
               class="filter-pill filter-pill-all <?php echo e(($extension ?? 'all') === 'all' ? 'active' : ''); ?>">
                <i class="bi bi-grid-3x3-gap"></i>
                Tous
            </a>
            <a href="<?php echo e(route('documents.index', array_merge(request()->except(['ext', 'page']), ['ext' => 'pdf']))); ?>" 
               class="filter-pill filter-pill-pdf <?php echo e(($extension ?? 'all') === 'pdf' ? 'active' : ''); ?>">
                <i class="bi bi-file-earmark-pdf"></i>
                PDF
            </a>
            <a href="<?php echo e(route('documents.index', array_merge(request()->except(['ext', 'page']), ['ext' => 'word']))); ?>" 
               class="filter-pill filter-pill-word <?php echo e(in_array($extension ?? '', ['doc', 'docx']) ? 'active' : ''); ?>">
                <i class="bi bi-file-earmark-word"></i>
                Word
            </a>
            <a href="<?php echo e(route('documents.index', array_merge(request()->except(['ext', 'page']), ['ext' => 'powerpoint']))); ?>" 
               class="filter-pill filter-pill-powerpoint <?php echo e(in_array($extension ?? '', ['ppt', 'pptx']) ? 'active' : ''); ?>">
                <i class="bi bi-file-earmark-ppt"></i>
                PowerPoint
            </a>
            <a href="<?php echo e(route('documents.index', array_merge(request()->except(['ext', 'page']), ['ext' => 'excel']))); ?>" 
               class="filter-pill filter-pill-excel <?php echo e(in_array($extension ?? '', ['xls', 'xlsx']) ? 'active' : ''); ?>">
                <i class="bi bi-file-earmark-excel"></i>
                Excel
            </a>
        </div>
    </div>
</div>


<?php if($documents->isEmpty()): ?>
    <div class="modern-card">
        <div class="modern-card-body">
            <div class="empty-state">
                <i class="bi bi-inbox empty-state-icon"></i>
                <div class="empty-state-title">Aucun document</div>
                <div class="empty-state-text">
                    <?php if(($search ?? '') || ($extension ?? 'all') !== 'all'): ?>
                        Aucun document ne correspond à vos critères de recherche.
                    <?php else: ?>
                        Aucun document enregistré pour le moment.
                    <?php endif; ?>
                </div>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\Document::class)): ?>
                    <a href="#uploadZone" class="btn btn-modern btn-modern-primary mt-3">
                        <i class="bi bi-upload"></i> Téléverser un document
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="row g-4">
        <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-4 col-lg-3">
                <div class="document-card-image-style">
                    <div class="document-icon-square <?php echo e(strtolower($document->extension)); ?>">
                        <span class="document-icon-text">
                            <?php if(strtolower($document->extension) === 'pdf'): ?>
                                PDF
                            <?php elseif(in_array(strtolower($document->extension), ['doc', 'docx'])): ?>
                                W
                            <?php elseif(in_array(strtolower($document->extension), ['xls', 'xlsx'])): ?>
                                X
                            <?php elseif(in_array(strtolower($document->extension), ['ppt', 'pptx'])): ?>
                                P
                            <?php else: ?>
                                <?php echo e(strtoupper(substr($document->extension, 0, 1))); ?>

                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="document-card-content">
                        <h6 class="document-title-image" title="<?php echo e($document->title); ?>">
                            <?php echo e(Str::limit($document->title, 45)); ?>

                        </h6>
                        <div class="document-details-image">
                            <?php echo e(number_format($document->file_size / 1024 / 1024, 1, ',', ' ')); ?> MB - 
                            <?php echo e($document->created_at?->format('d/m/Y')); ?> - 
                            <?php echo e($document->uploader?->first_name ?? ''); ?> <?php echo e($document->uploader?->last_name ?? $document->uploader?->name ?? 'N/A'); ?>

                        </div>
                        <?php if($document->type): ?>
                            <?php
                                $typeColors = [
                                    'Ordre du jour' => ['bg' => '#fed7aa', 'text' => '#9a3412'],
                                    'Procès-verbal' => ['bg' => '#d1fae5', 'text' => '#065f46'],
                                    'Procès verbale' => ['bg' => '#d1fae5', 'text' => '#065f46'],
                                    'Rapport' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                                    'Présentation' => ['bg' => '#fce7f3', 'text' => '#9f1239'],
                                    'Note verbale' => ['bg' => '#e0e7ff', 'text' => '#3730a3'],
                                    'Projet de décision' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                ];
                                $typeName = $document->type->name;
                                $colors = $typeColors[$typeName] ?? ['bg' => '#f3f4f6', 'text' => '#374151'];
                            ?>
                            <div class="document-type-badge-image" style="background: <?php echo e($colors['bg']); ?>; color: <?php echo e($colors['text']); ?>;">
                                <?php echo e($typeName); ?>

                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <?php if($documents->hasPages()): ?>
        <div class="modern-card-footer mt-4">
            <div class="small text-muted">
                Affichage de <?php echo e($documents->firstItem()); ?> à <?php echo e($documents->lastItem()); ?> 
                sur <?php echo e($documents->total()); ?> document<?php echo e($documents->total() > 1 ? 's' : ''); ?>

            </div>
            <div class="pagination-modern">
                <?php echo e($documents->appends(request()->query())->links()); ?>

            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Zone de dépôt de documents - Design professionnel */
    .document-upload-zone {
        border: 3px dashed #cbd5e1;
        border-radius: 20px;
        padding: 4rem 2rem;
        text-align: center;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 50%, #ffffff 100%);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .document-upload-zone::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.1), transparent);
        transition: left 0.5s;
    }

    .document-upload-zone:hover::before {
        left: 100%;
    }

    .document-upload-zone:hover {
        border-color: #667eea;
        background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 50%, #f0f4ff 100%);
        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.15);
        transform: translateY(-2px);
    }

    .document-upload-zone.dragover {
        border-color: #667eea;
        background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 50%, #eef2ff 100%);
        transform: scale(1.02);
        box-shadow: 0 15px 50px rgba(102, 126, 234, 0.25);
    }

    .upload-zone-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }

    .upload-icon {
        font-size: 5rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 1.5rem;
        animation: float 3s ease-in-out infinite;
        filter: drop-shadow(0 4px 8px rgba(102, 126, 234, 0.2));
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }

    .upload-title {
        font-size: 1.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #1e293b 0%, #475569 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0;
        letter-spacing: -0.02em;
    }

    .upload-subtitle {
        font-size: 1.1rem;
        color: #64748b;
        margin: 0.5rem 0;
        font-weight: 500;
    }

    .upload-info {
        border-top: 2px solid #e2e8f0;
        padding-top: 1.5rem;
        margin-top: 2rem;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, transparent 100%);
        border-radius: 0 0 16px 16px;
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .upload-info p {
        margin: 0.5rem 0;
        color: #475569;
        font-weight: 500;
    }

    .upload-info i {
        color: #667eea;
    }

    /* Filtres par type de document */
    .document-type-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .filter-pill {
        padding: 0.75rem 1.75rem;
        border-radius: 25px;
        background: #ffffff;
        border: 2px solid #e2e8f0;
        color: #64748b;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        position: relative;
        overflow: hidden;
    }

    .filter-pill i {
        font-size: 1rem;
    }

    /* Filtre "Tous" */
    .filter-pill-all {
        border-color: #cbd5e1;
    }

    .filter-pill-all:hover {
        border-color: #667eea;
        color: #667eea;
        background: #f0f4ff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
    }

    .filter-pill-all.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: transparent;
        color: #ffffff;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    /* Filtre PDF - Rouge */
    .filter-pill-pdf {
        border-color: #fecaca;
        color: #991b1b;
    }

    .filter-pill-pdf:hover {
        border-color: #ef4444;
        color: #dc2626;
        background: #fef2f2;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
    }

    .filter-pill-pdf.active {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border-color: transparent;
        color: #ffffff;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);
    }

    /* Filtre Word - Bleu */
    .filter-pill-word {
        border-color: #bfdbfe;
        color: #1e40af;
    }

    .filter-pill-word:hover {
        border-color: #3b82f6;
        color: #2563eb;
        background: #eff6ff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    }

    .filter-pill-word.active {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border-color: transparent;
        color: #ffffff;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
    }

    /* Filtre PowerPoint - Orange */
    .filter-pill-powerpoint {
        border-color: #fed7aa;
        color: #9a3412;
    }

    .filter-pill-powerpoint:hover {
        border-color: #f59e0b;
        color: #d97706;
        background: #fffbeb;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2);
    }

    .filter-pill-powerpoint.active {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        border-color: transparent;
        color: #ffffff;
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);
    }

    /* Filtre Excel - Vert */
    .filter-pill-excel {
        border-color: #bbf7d0;
        color: #065f46;
    }

    .filter-pill-excel:hover {
        border-color: #10b981;
        color: #059669;
        background: #f0fdf4;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
    }

    .filter-pill-excel.active {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-color: transparent;
        color: #ffffff;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
    }

    /* Cartes de documents - Design exact selon image */
    .document-card-image-style {
        background: #ffffff;
        border-radius: 12px;
        padding: 1.25rem;
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #e5e7eb;
        cursor: pointer;
    }

    .document-card-image-style:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        border-color: #d1d5db;
    }

    .document-icon-square {
        width: 60px;
        height: 60px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        font-weight: 700;
        font-size: 0.875rem;
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    }

    .document-icon-square.pdf {
        background: #ef4444;
    }

    .document-icon-square.doc,
    .document-icon-square.docx {
        background: #3b82f6;
    }

    .document-icon-square.xls,
    .document-icon-square.xlsx {
        background: #10b981;
    }

    .document-icon-square.ppt,
    .document-icon-square.pptx {
        background: #f59e0b;
    }

    .document-icon-text {
        color: #ffffff;
        font-weight: 700;
        font-size: 0.875rem;
        letter-spacing: 0.5px;
    }

    .document-card-content {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .document-title-image {
        font-size: 0.9rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.625rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 2.5rem;
    }

    .document-details-image {
        font-size: 0.8rem;
        color: #6b7280;
        margin-bottom: 0.875rem;
        line-height: 1.5;
    }

    .document-type-badge-image {
        display: inline-block;
        padding: 0.375rem 0.875rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-top: auto;
    }

    /* Badge de type de document - Design selon image */
    .document-type-badge-image {
        display: inline-block;
        padding: 0.375rem 0.875rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-top: auto;
        width: fit-content;
    }

    .required {
        color: #ef4444;
        font-weight: 700;
    }

    @media (max-width: 768px) {
        .document-upload-zone {
            padding: 2rem 1rem;
        }

        .upload-icon {
            font-size: 3rem;
        }

        .document-icon-wrapper {
            width: 60px;
            height: 60px;
            font-size: 1rem;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Gestion du drag & drop
    const uploadZone = document.getElementById('uploadZone');
    const fileInput = document.getElementById('fileInput');
    const documentDetails = document.getElementById('documentDetails');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');

    if (uploadZone && fileInput) {
        // Click sur la zone
        uploadZone.addEventListener('click', () => fileInput.click());

        // Drag & drop
        uploadZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadZone.classList.add('dragover');
        });

        uploadZone.addEventListener('dragleave', () => {
            uploadZone.classList.remove('dragover');
        });

        uploadZone.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadZone.classList.remove('dragover');
            
            if (e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                handleFileSelect();
            }
        });

        // Sélection de fichier
        fileInput.addEventListener('change', handleFileSelect);
    }

    function handleFileSelect() {
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const maxSize = 500 * 1024 * 1024; // 500 MB
            
            if (file.size > maxSize) {
                alert('Le fichier est trop volumineux. Taille maximale: 500 MB');
                fileInput.value = '';
                return;
            }

            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            fileInfo.classList.remove('d-none');
            documentDetails.classList.remove('d-none');
            
            // Auto-remplir le titre si vide
            const titleInput = document.querySelector('input[name="title"]');
            if (titleInput && !titleInput.value) {
                titleInput.value = file.name.replace(/\.[^/.]+$/, '');
            }
        }
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }

    function resetUpload() {
        fileInput.value = '';
        fileInfo.classList.add('d-none');
        documentDetails.classList.add('d-none');
        document.querySelector('input[name="title"]').value = '';
        document.querySelector('select[name="document_type_id"]').value = '';
        document.querySelector('select[name="meeting_id"]').value = '';
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views/documents/index.blade.php ENDPATH**/ ?>