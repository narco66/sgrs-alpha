<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 fw-bold">Créer une nouvelle réunion</h2>
            <p class="text-muted mb-0 small">Conforme au modèle institutionnel CEEAC</p>
        </div>
        <a href="<?php echo e(route('meetings.index')); ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Erreurs détectées :</strong>
            <ul class="mb-0 mt-2">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('meetings.store')); ?>" id="meetingForm" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>

        
        <ul class="nav nav-tabs mb-4" id="meetingTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">
                    <i class="bi bi-info-circle"></i> Informations générales
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="committee-tab" data-bs-toggle="tab" data-bs-target="#committee" type="button" role="tab">
                    <i class="bi bi-people"></i> Comité d'organisation
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="terms-tab" data-bs-toggle="tab" data-bs-target="#terms" type="button" role="tab">
                    <i class="bi bi-file-earmark-text"></i> Cahier des charges
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="delegations-tab" data-bs-toggle="tab" data-bs-target="#delegations" type="button" role="tab">
                    <i class="bi bi-building"></i> Délégations
                </button>
            </li>
        </ul>

        <div class="tab-content" id="meetingTabsContent">
            
            <div class="tab-pane fade show active" id="general" role="tabpanel">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="bi bi-info-circle text-primary"></i> Informations de la réunion
                        </h5>

                        <div class="row g-3">
                            
                            <div class="col-md-12">
                                <label class="form-label">Titre de la réunion <span class="text-danger">*</span></label>
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
                                       placeholder="Ex: Réunion du Conseil des Ministres de la CEEAC"
                                       value="<?php echo e(old('title')); ?>"
                                       required>
                                <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            
                            <div class="col-md-6">
                                <label class="form-label">Type de réunion <span class="text-danger">*</span></label>
                                <select name="meeting_type_id" class="form-select <?php $__errorArgs = ['meeting_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                    <option value="">Sélectionner un type</option>
                                    <?php $__currentLoopData = $meetingTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($type->id); ?>" <?php if(old('meeting_type_id') == $type->id): echo 'selected'; endif; ?>>
                                            <?php echo e($type->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['meeting_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Comité</label>
                                <select name="committee_id" class="form-select <?php $__errorArgs = ['committee_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Aucun comité</option>
                                    <?php $__currentLoopData = $committees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $committee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($committee->id); ?>" <?php if(old('committee_id') == $committee->id): echo 'selected'; endif; ?>>
                                            <?php echo e($committee->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['committee_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            
                            <div class="col-md-4">
                                <label class="form-label">Date <span class="text-danger">*</span></label>
                                <input type="date"
                                       name="date"
                                       class="form-control <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       value="<?php echo e(old('date', now()->format('Y-m-d'))); ?>"
                                       required>
                                <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Heure <span class="text-danger">*</span></label>
                                <input type="time"
                                       name="time"
                                       class="form-control <?php $__errorArgs = ['time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       value="<?php echo e(old('time')); ?>"
                                       required>
                                <?php $__errorArgs = ['time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Durée <span class="text-danger">*</span></label>
                                <select name="duration_minutes" class="form-select <?php $__errorArgs = ['duration_minutes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                    <?php $__currentLoopData = [30, 60, 90, 120, 180, 240]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $minutes): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($minutes); ?>" <?php if(old('duration_minutes', 60) == $minutes): echo 'selected'; endif; ?>>
                                            <?php echo e($minutes); ?> minutes
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['duration_minutes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            
                            <div class="col-md-6">
                                <label class="form-label">Configuration <span class="text-danger">*</span></label>
                                <select name="configuration" class="form-select <?php $__errorArgs = ['configuration'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                    <option value="presentiel" <?php if(old('configuration') === 'presentiel'): echo 'selected'; endif; ?>>Présentiel</option>
                                    <option value="hybride" <?php if(old('configuration') === 'hybride'): echo 'selected'; endif; ?>>Hybride</option>
                                    <option value="visioconference" <?php if(old('configuration') === 'visioconference'): echo 'selected'; endif; ?>>Visioconférence</option>
                                </select>
                                <?php $__errorArgs = ['configuration'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Pays hôte</label>
                                <input type="text"
                                       name="host_country"
                                       class="form-control <?php $__errorArgs = ['host_country'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       placeholder="Ex: République du Congo"
                                       value="<?php echo e(old('host_country')); ?>">
                                <?php $__errorArgs = ['host_country'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="form-text">Indiquez le pays hôte si la réunion se tient dans un État membre.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Salle de réunion</label>
                                <select name="room_id" class="form-select <?php $__errorArgs = ['room_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Sélectionner une salle (optionnel)</option>
                                    <?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($room->id); ?>" <?php if(old('room_id') == $room->id): echo 'selected'; endif; ?>>
                                            <?php echo e($room->name); ?> <?php if($room->capacity): ?> (<?php echo e($room->capacity); ?> places) <?php endif; ?>
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['room_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea name="description"
                                          rows="4"
                                          class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                          placeholder="Description de la réunion, ordre du jour préliminaire, etc."><?php echo e(old('description')); ?></textarea>
                                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            
                            <div class="col-12">
                                <label class="form-label">Ordre du jour</label>
                                <textarea name="agenda"
                                          rows="6"
                                          class="form-control <?php $__errorArgs = ['agenda'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                          placeholder="Points à l'ordre du jour (un par ligne)"><?php echo e(old('agenda')); ?></textarea>
                                <?php $__errorArgs = ['agenda'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            
                            <div class="col-md-6">
                                <label class="form-label">Rappel avant la réunion</label>
                                <select name="reminder_minutes_before" class="form-select">
                                    <?php
                                        $reminderValues = [0 => 'Aucun', 5 => '5 minutes', 10 => '10 minutes', 15 => '15 minutes', 30 => '30 minutes', 60 => '1 heure', 120 => '2 heures', 1440 => '1 jour'];
                                    ?>
                                    <?php $__currentLoopData = $reminderValues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val); ?>" <?php if(old('reminder_minutes_before', 0) == $val): echo 'selected'; endif; ?>>
                                            <?php echo e($label); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="tab-pane fade" id="committee" role="tabpanel">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="bi bi-people text-primary"></i> Comité d'organisation de la réunion
                        </h5>
                        <p class="text-muted mb-4">
                            Le comité d'organisation est composé de fonctionnaires de la CEEAC et, si la réunion se tient dans un État membre, 
                            de fonctionnaires du pays hôte.
                        </p>

                        <div class="row g-3">
                            
                            <div class="col-md-12">
                                <label class="form-label">Comité d'organisation <span class="text-muted small">(optionnel)</span></label>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="committee_option" id="no_committee" value="" checked>
                                    <label class="form-check-label" for="no_committee">
                                        Aucun comité (peut être ajouté plus tard)
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="committee_option" id="use_existing_committee" value="existing">
                                    <label class="form-check-label" for="use_existing_committee">
                                        Utiliser un comité existant
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="committee_option" id="create_new_committee" value="new">
                                    <label class="form-check-label" for="create_new_committee">
                                        Créer un nouveau comité d'organisation
                                    </label>
                                </div>
                            </div>

                            
                            <div class="col-md-12" id="existing_committee_section" style="display: none;">
                                <label class="form-label">Sélectionner un comité <span class="text-danger">*</span></label>
                                <select name="organization_committee_id" 
                                        id="organization_committee_select"
                                        class="form-select <?php $__errorArgs = ['organization_committee_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Sélectionner un comité</option>
                                    <?php $__currentLoopData = $availableCommittees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $committee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($committee->id); ?>" 
                                                data-name="<?php echo e($committee->name); ?>"
                                                data-description="<?php echo e($committee->description ?? ''); ?>"
                                                data-host-country="<?php echo e($committee->host_country ?? ''); ?>"
                                                data-members-count="<?php echo e($committee->members->count()); ?>"
                                                <?php if(old('organization_committee_id') == $committee->id): echo 'selected'; endif; ?>>
                                            <?php echo e($committee->name); ?> 
                                            <?php if($committee->members->count() > 0): ?>
                                                (<?php echo e($committee->members->count()); ?> membre<?php echo e($committee->members->count() > 1 ? 's' : ''); ?>)
                                            <?php endif; ?>
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['organization_committee_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="form-text">
                                    <a href="<?php echo e(route('organization-committees.create')); ?>" target="_blank" class="text-decoration-none">
                                        <i class="bi bi-plus-circle"></i> Créer un nouveau comité
                                    </a>
                                </div>

                                
                                <div id="selected_committee_info" class="mt-3" style="display: none;">
                                    <div class="card border-info">
                                        <div class="card-header bg-info text-white">
                                            <h6 class="mb-0">
                                                <i class="bi bi-info-circle"></i> Informations du comité sélectionné
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div id="committee_info_content">
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="col-md-12" id="new_committee_section" style="display: none;">
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle"></i> 
                                    Le comité d'organisation sera créé et associé à cette réunion. 
                                    Vous pourrez ajouter les membres après la création de la réunion.
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Nom du comité <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="new_committee_name" 
                                           class="form-control <?php $__errorArgs = ['new_committee_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           placeholder="Ex: Comité d'organisation - Réunion des Ministres"
                                           value="<?php echo e(old('new_committee_name')); ?>">
                                    <?php $__errorArgs = ['new_committee_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="new_committee_description" class="form-control" rows="3" placeholder="Description du comité d'organisation"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Pays hôte (si applicable)</label>
                                    <input type="text" 
                                           name="new_committee_host_country" 
                                           class="form-control" 
                                           placeholder="Ex: République du Congo"
                                           value="<?php echo e(old('new_committee_host_country')); ?>">
                                    <div class="form-text">Indiquez le pays hôte si la réunion se tient dans un État membre</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="tab-pane fade" id="terms" role="tabpanel">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="bi bi-file-earmark-text text-primary"></i> Cahier des charges
                        </h5>
                        <p class="text-muted mb-4">
                            Le cahier des charges définit le partage des responsabilités et des charges financières/logistiques 
                            entre la CEEAC et le pays hôte. Il peut être créé après la création de la réunion.
                        </p>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> 
                            <strong>Note :</strong> Le cahier des charges peut être créé et géré après la création de la réunion 
                            depuis la page de détails de la réunion. Cette étape est optionnelle lors de la création.
                        </div>

                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="create_terms_of_reference" id="create_terms_of_reference" value="1">
                                    <label class="form-check-label" for="create_terms_of_reference">
                                        Créer un cahier des charges maintenant
                                    </label>
                                </div>
                            </div>

                            <div id="terms_fields" style="display: none;">
                                <div class="col-md-6">
                                    <label class="form-label">Pays hôte <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="terms_host_country" 
                                           class="form-control <?php $__errorArgs = ['terms_host_country'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           placeholder="Ex: République du Congo"
                                          value="<?php echo e(old('terms_host_country', old('host_country'))); ?>">
                                    <?php $__errorArgs = ['terms_host_country'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Date de signature prévue</label>
                                    <input type="date" name="terms_signature_date" class="form-control">
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Responsabilités CEEAC</label>
                                    <textarea name="terms_responsibilities_ceeac" 
                                              class="form-control" 
                                              rows="4" 
                                              placeholder="Décrivez les responsabilités de la CEEAC..."></textarea>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Responsabilités pays hôte</label>
                                    <textarea name="terms_responsibilities_host" 
                                              class="form-control" 
                                              rows="4" 
                                              placeholder="Décrivez les responsabilités du pays hôte..."></textarea>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Partage financier</label>
                                    <textarea name="terms_financial_sharing" 
                                              class="form-control" 
                                              rows="3" 
                                              placeholder="Décrivez le partage des charges financières..."></textarea>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Partage logistique</label>
                                    <textarea name="terms_logistical_sharing" 
                                              class="form-control" 
                                              rows="3" 
                                              placeholder="Décrivez le partage des charges logistiques..."></textarea>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">
                                        <i class="bi bi-file-earmark-pdf"></i> Document physique signé (optionnel)
                                    </label>
                                    <input type="file" 
                                           name="terms_signed_document" 
                                           class="form-control"
                                           accept=".pdf,.jpg,.jpeg,.png">
                                    <div class="form-text">
                                        Vous pouvez joindre le document physique signé entre les deux parties (PDF ou image scannée). 
                                        Formats acceptés : PDF, JPG, JPEG, PNG. Taille maximale : 10 MB.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="tab-pane fade" id="delegations" role="tabpanel">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="bi bi-building text-primary"></i> Délégations participantes
                        </h5>
                        <p class="text-muted mb-4">
                            <strong>Important :</strong> La participation se fait par délégations institutionnelles (États membres, 
                            organisations internationales, partenaires), et non par participants individuels.
                        </p>

                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> 
                            <strong>Note :</strong> Les délégations peuvent être ajoutées après la création de la réunion 
                            depuis la page de détails. Cette étape est optionnelle lors de la création.
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">Délégations à inviter</h6>
                                    <a href="<?php echo e(route('delegations.create')); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-plus-circle"></i> Créer une nouvelle délégation
                                    </a>
                                </div>

                                <div class="form-text mb-3">
                                    Les délégations existantes peuvent être associées à cette réunion après sa création.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="d-flex justify-content-between align-items-center mt-4">
            <a href="<?php echo e(route('meetings.index')); ?>" class="btn btn-outline-secondary">
                <i class="bi bi-x-circle"></i> Annuler
            </a>
            <div>
                <button type="button" class="btn btn-outline-primary" id="saveDraftBtn">
                    <i class="bi bi-save"></i> Enregistrer comme brouillon
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Créer la réunion
                </button>
            </div>
        </div>
    </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('meetingForm');
    
    // Fonction pour afficher un message de confirmation temporaire
    function showSuccessMessage(message, tabName) {
        const tabButton = document.querySelector(`#${tabName}-tab`);
        if (tabButton) {
            const originalHtml = tabButton.innerHTML;
            const icon = tabButton.querySelector('i').outerHTML;
            tabButton.innerHTML = `${icon} ${message}`;
            tabButton.classList.add('text-success');
            
            setTimeout(() => {
                tabButton.innerHTML = originalHtml;
                tabButton.classList.remove('text-success');
            }, 3000);
        }
    }

    // Gestion de l'affichage du formulaire de comité
    const noCommittee = document.getElementById('no_committee');
    const useExisting = document.getElementById('use_existing_committee');
    const createNew = document.getElementById('create_new_committee');
    const existingSection = document.getElementById('existing_committee_section');
    const newSection = document.getElementById('new_committee_section');

    function updateCommitteeSections() {
        if (noCommittee && noCommittee.checked) {
            existingSection.style.display = 'none';
            newSection.style.display = 'none';
        } else if (useExisting && useExisting.checked) {
            existingSection.style.display = 'block';
            newSection.style.display = 'none';
        } else if (createNew && createNew.checked) {
            existingSection.style.display = 'none';
            newSection.style.display = 'block';
        }
    }

    if (noCommittee) noCommittee.addEventListener('change', updateCommitteeSections);
    if (useExisting) useExisting.addEventListener('change', updateCommitteeSections);
    if (createNew) createNew.addEventListener('change', updateCommitteeSections);
    
    // Initialiser l'affichage au chargement
    updateCommitteeSections();

    // Gestion de l'affichage des champs du cahier des charges
    const createTermsCheckbox = document.getElementById('create_terms_of_reference');
    const termsFields = document.getElementById('terms_fields');
    const generalHostInput = document.querySelector('input[name=\"host_country\"]');
    const termsHostInput = document.querySelector('input[name=\"terms_host_country\"]');

    function syncHostCountryToTerms() {
        if (!createTermsCheckbox || !createTermsCheckbox.checked || !termsHostInput) {
            return;
        }

        const hostValue = (generalHostInput?.value || '').trim();
        const currentTermsValue = (termsHostInput.value || '').trim();
        const autoFilledValue = termsHostInput.dataset.autoFilledValue || '';

        // Auto-fill only if empty or previously auto-filled to avoid overriding user edits.
        if (hostValue && (currentTermsValue === '' || currentTermsValue === autoFilledValue)) {
            termsHostInput.value = hostValue;
            termsHostInput.dataset.autoFilledValue = hostValue;
        }
    }

    if (createTermsCheckbox && termsFields) {
        createTermsCheckbox.addEventListener('change', function() {
            if (this.checked) {
                termsFields.style.display = 'block';
                syncHostCountryToTerms();
                // Validation du pays hôte si onglet actif
                const hostCountryInput = document.querySelector('input[name="terms_host_country"]');
                if (hostCountryInput && !hostCountryInput.value.trim()) {
                    hostCountryInput.focus();
                }
            } else {
                termsFields.style.display = 'none';
            }
        });
    }

    if (generalHostInput) {
        generalHostInput.addEventListener('input', syncHostCountryToTerms);
        generalHostInput.addEventListener('change', syncHostCountryToTerms);
    }

    // Initial sync on load (useful when checkbox is pre-checked or host already saisi).
    syncHostCountryToTerms();

    // Validation des onglets avant soumission avec messages spécifiques
    form.addEventListener('submit', function(e) {
        let isValid = true;
        let errorMessage = '';
        let errorTab = 'general';

        // Validation onglet général
        const title = document.querySelector('input[name="title"]');
        const meetingType = document.querySelector('select[name="meeting_type_id"]');
        const date = document.querySelector('input[name="date"]');
        const time = document.querySelector('input[name="time"]');
        
        if (!title || !title.value.trim()) {
            isValid = false;
            errorMessage = 'Le titre de la réunion est obligatoire.';
            errorTab = 'general';
        } else if (!meetingType || !meetingType.value) {
            isValid = false;
            errorMessage = 'Le type de réunion est obligatoire.';
            errorTab = 'general';
        } else if (!date || !date.value) {
            isValid = false;
            errorMessage = 'La date de la réunion est obligatoire.';
            errorTab = 'general';
        } else if (!time || !time.value) {
            isValid = false;
            errorMessage = 'L\'heure de la réunion est obligatoire.';
            errorTab = 'general';
        }

        // Validation onglet comité
        if (isValid && useExisting && useExisting.checked) {
            const committeeSelect = document.getElementById('organization_committee_select');
            if (committeeSelect && !committeeSelect.value) {
                isValid = false;
                errorMessage = 'Veuillez sélectionner un comité d\'organisation ou choisir une autre option.';
                errorTab = 'committee';
            }
        }

        if (isValid && createNew && createNew.checked) {
            const newCommitteeName = document.querySelector('input[name="new_committee_name"]');
            if (newCommitteeName && !newCommitteeName.value.trim()) {
                isValid = false;
                errorMessage = 'Le nom du nouveau comité d\'organisation est obligatoire.';
                errorTab = 'committee';
            }
        }

        // Validation onglet cahier des charges
        if (isValid && createTermsCheckbox && createTermsCheckbox.checked) {
            const hostCountry = document.querySelector('input[name="terms_host_country"]');
            if (hostCountry && !hostCountry.value.trim()) {
                isValid = false;
                errorMessage = 'Le pays hôte est obligatoire pour créer un cahier des charges.';
                errorTab = 'terms';
            }
        }

        if (!isValid) {
            e.preventDefault();
            // Afficher l'erreur
            if (!document.querySelector('.alert-danger')) {
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-danger alert-dismissible fade show';
                alertDiv.innerHTML = `
                    <strong>Erreur de validation :</strong> ${errorMessage}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                form.insertBefore(alertDiv, form.firstChild);
            }
            
            // Activer l'onglet avec l'erreur
            const tabButton = document.querySelector(`#${errorTab}-tab`);
            if (tabButton) {
                const tab = new bootstrap.Tab(tabButton);
                tab.show();
            }
            
            return false;
        }

        // Afficher message de confirmation avant soumission
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enregistrement en cours...';
            submitBtn.disabled = true;
        }
    });

    // Bouton "Enregistrer comme brouillon"
    const saveDraftBtn = document.getElementById('saveDraftBtn');
    if (saveDraftBtn) {
        saveDraftBtn.addEventListener('click', function() {
            // Validation minimale pour brouillon
            const title = document.querySelector('input[name="title"]');
            if (!title || !title.value.trim()) {
                alert('Veuillez remplir au moins le titre de la réunion pour enregistrer un brouillon.');
                const tabButton = document.querySelector('#general-tab');
                if (tabButton) {
                    const tab = new bootstrap.Tab(tabButton);
                    tab.show();
                }
                return false;
            }

            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = 'draft';
            form.appendChild(statusInput);
            
            // Message de confirmation
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enregistrement du brouillon...';
            this.disabled = true;
            form.submit();
        });
    }

    // Sauvegarder l'onglet actif avant soumission
    const tabs = document.querySelectorAll('#meetingTabs button[data-bs-toggle="tab"]');
    tabs.forEach(tab => {
        tab.addEventListener('shown.bs.tab', function() {
            const activeTab = this.getAttribute('data-bs-target').replace('#', '');
            let hiddenInput = document.querySelector('input[name="active_tab"]');
            if (!hiddenInput) {
                hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'active_tab';
                form.appendChild(hiddenInput);
            }
            hiddenInput.value = activeTab;
        });
    });

    // Afficher les informations du comité sélectionné
    const committeeSelect = document.getElementById('organization_committee_select');
    const committeeInfo = document.getElementById('selected_committee_info');
    const committeeInfoContent = document.getElementById('committee_info_content');
    
    if (committeeSelect && committeeInfo && committeeInfoContent) {
        committeeSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            
            if (selectedOption.value) {
                const name = selectedOption.getAttribute('data-name');
                const description = selectedOption.getAttribute('data-description');
                const hostCountry = selectedOption.getAttribute('data-host-country');
                const membersCount = selectedOption.getAttribute('data-members-count');
                
                let html = `<h6 class="mb-2">${name || 'Comité sélectionné'}</h6>`;
                
                if (description) {
                    html += `<p class="text-muted small mb-2">${description}</p>`;
                }
                
                if (hostCountry) {
                    html += `<p class="mb-2"><i class="bi bi-geo-alt"></i> <strong>Pays hôte :</strong> ${hostCountry}</p>`;
                }
                
                html += `<p class="mb-0"><i class="bi bi-people"></i> <strong>Membres :</strong> ${membersCount || 0} membre${membersCount > 1 ? 's' : ''}</p>`;
                
                if (membersCount == 0) {
                    html += `<div class="alert alert-warning mt-2 mb-0"><i class="bi bi-exclamation-triangle"></i> Aucun membre n'a encore été ajouté à ce comité.</div>`;
                }
                
                committeeInfoContent.innerHTML = html;
                committeeInfo.style.display = 'block';
            } else {
                committeeInfo.style.display = 'none';
            }
        });
        
        // Déclencher l'événement au chargement si un comité est déjà sélectionné
        if (committeeSelect.value) {
            committeeSelect.dispatchEvent(new Event('change'));
        }
    }

    // Afficher les messages de succès de session s'ils existent
    <?php if(session('success')): ?>
        const successAlert = document.createElement('div');
        successAlert.className = 'alert alert-success alert-dismissible fade show';
        successAlert.innerHTML = `
            <i class="bi bi-check-circle"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        form.insertBefore(successAlert, form.firstChild);
        
        // Activer l'onglet approprié si spécifié
        <?php if(session('active_tab')): ?>
            const activeTabFromSession = '<?php echo e(session("active_tab")); ?>';
            if (activeTabFromSession && activeTabFromSession !== 'general') {
                const tabButton = document.querySelector(`#${activeTabFromSession}-tab`);
                if (tabButton) {
                    const tab = new bootstrap.Tab(tabButton);
                    tab.show();
                }
            }
        <?php endif; ?>
    <?php endif; ?>
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\meetings\create.blade.php ENDPATH**/ ?>