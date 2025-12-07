

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 fw-bold">Modifier la réunion</h2>
            <p class="text-muted mb-0 small"><?php echo e($meeting->title); ?></p>
        </div>
        <a href="<?php echo e(route('meetings.show', $meeting)); ?>" class="btn btn-outline-secondary">
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

    <form method="POST" action="<?php echo e(route('meetings.update', $meeting)); ?>" id="meetingForm" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        
        <?php
            $activeTab = session('active_tab', 'general');
            $reminderValues = [0 => 'Aucun', 5 => '5 minutes', 10 => '10 minutes', 15 => '15 minutes', 30 => '30 minutes', 60 => '1 heure', 120 => '2 heures', 1440 => '1 jour'];
            $currentCommittee = $meeting->organizationCommittee ?? null;
            $committeeOption = old('committee_option', $currentCommittee ? 'existing' : '');
            $hasTerms = !empty($meeting->termsOfReference);
        ?>
        <ul class="nav nav-tabs mb-4" id="meetingTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link <?php echo e($activeTab === 'general' ? 'active' : ''); ?>" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">
                    <i class="bi bi-info-circle"></i> Informations générales
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link <?php echo e($activeTab === 'committee' ? 'active' : ''); ?>" id="committee-tab" data-bs-toggle="tab" data-bs-target="#committee" type="button" role="tab">
                    <i class="bi bi-people"></i> Comité d'organisation
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link <?php echo e($activeTab === 'terms' ? 'active' : ''); ?>" id="terms-tab" data-bs-toggle="tab" data-bs-target="#terms" type="button" role="tab">
                    <i class="bi bi-file-earmark-text"></i> Cahier des charges
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link <?php echo e($activeTab === 'delegations' ? 'active' : ''); ?>" id="delegations-tab" data-bs-toggle="tab" data-bs-target="#delegations" type="button" role="tab">
                    <i class="bi bi-building"></i> Délégations
                </button>
            </li>
        </ul>

        <div class="tab-content" id="meetingTabsContent">
            
            <div class="tab-pane fade <?php echo e($activeTab === 'general' ? 'show active' : ''); ?>" id="general" role="tabpanel">
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
                                       value="<?php echo e(old('title', $meeting->title)); ?>"
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
                                        <option value="<?php echo e($type->id); ?>" <?php if(old('meeting_type_id', $meeting->meeting_type_id) == $type->id): echo 'selected'; endif; ?>>
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
                                        <option value="<?php echo e($committee->id); ?>" <?php if(old('committee_id', $meeting->committee_id) == $committee->id): echo 'selected'; endif; ?>>
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
                                       value="<?php echo e(old('date', $meeting->start_at ? $meeting->start_at->format('Y-m-d') : now()->format('Y-m-d'))); ?>"
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
                                       value="<?php echo e(old('time', $meeting->start_at ? $meeting->start_at->format('H:i') : '')); ?>"
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
                                        <option value="<?php echo e($minutes); ?>" <?php if(old('duration_minutes', $meeting->duration_minutes ?? 60) == $minutes): echo 'selected'; endif; ?>>
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
                                    <option value="presentiel" <?php if(old('configuration', $meeting->configuration ?? 'presentiel') === 'presentiel'): echo 'selected'; endif; ?>>Présentiel</option>
                                    <option value="hybride" <?php if(old('configuration', $meeting->configuration ?? 'presentiel') === 'hybride'): echo 'selected'; endif; ?>>Hybride</option>
                                    <option value="visioconference" <?php if(old('configuration', $meeting->configuration ?? 'presentiel') === 'visioconference'): echo 'selected'; endif; ?>>Visioconférence</option>
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
                                        <option value="<?php echo e($room->id); ?>" <?php if(old('room_id', $meeting->room_id) == $room->id): echo 'selected'; endif; ?>>
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

                            <?php
                                $statusOptions = $availableStatuses ?? \App\Enums\MeetingStatus::cases();
                            ?>
                            <div class="col-md-6">
                                <label class="form-label">Statut de la réunion</label>
                                <select name="status" class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($status->value); ?>" <?php if(old('status', $meeting->status) === $status->value): echo 'selected'; endif; ?>>
                                            <?php echo e($status->label()); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="form-text">Permet de passer en cours, terminée, annulée, etc.</div>
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
                                          placeholder="Description de la réunion, ordre du jour préliminaire, etc."><?php echo e(old('description', $meeting->description)); ?></textarea>
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
                                          placeholder="Points à l'ordre du jour (un par ligne)"><?php echo e(old('agenda', $meeting->agenda)); ?></textarea>
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
                                    <?php $__currentLoopData = $reminderValues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val); ?>" <?php if(old('reminder_minutes_before', $meeting->reminder_minutes_before ?? 0) == $val): echo 'selected'; endif; ?>>
                                            <?php echo e($label); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="tab-pane fade <?php echo e($activeTab === 'committee' ? 'show active' : ''); ?>" id="committee" role="tabpanel">
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
                                    <input class="form-check-input" type="radio" name="committee_option" id="no_committee" value="" <?php if($committeeOption === ''): echo 'checked'; endif; ?>>
                                    <label class="form-check-label" for="no_committee">
                                        Aucun comité
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="committee_option" id="use_existing_committee" value="existing" <?php if($committeeOption === 'existing'): echo 'checked'; endif; ?>>
                                    <label class="form-check-label" for="use_existing_committee">
                                        Utiliser un comité existant
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="committee_option" id="create_new_committee" value="new" <?php if($committeeOption === 'new'): echo 'checked'; endif; ?>>
                                    <label class="form-check-label" for="create_new_committee">
                                        Créer un nouveau comité d'organisation
                                    </label>
                                </div>
                            </div>

                            
                            <div class="col-md-12" id="existing_committee_section" style="display: <?php echo e($committeeOption === 'existing' ? 'block' : 'none'); ?>;">
                                <label class="form-label">Sélectionner un comité <span class="text-danger">*</span></label>
                                <select name="organization_committee_id" class="form-select <?php $__errorArgs = ['organization_committee_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Sélectionner un comité</option>
                                    <?php $__currentLoopData = $availableCommittees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $committee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($committee->id); ?>" <?php if(old('organization_committee_id', $currentCommittee?->id) == $committee->id): echo 'selected'; endif; ?>>
                                            <?php echo e($committee->name); ?>

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
                                    <a href="<?php echo e(route('organization-committees.create', ['meeting_id' => $meeting->id])); ?>" target="_blank" class="text-decoration-none">
                                        <i class="bi bi-plus-circle"></i> Créer un nouveau comité
                                    </a>
                                </div>
                            </div>

                            
                            <div class="col-md-12" id="new_committee_section" style="display: <?php echo e($committeeOption === 'new' ? 'block' : 'none'); ?>;">
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle"></i> 
                                    Le comité d'organisation sera créé et associé à cette réunion.
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
                                    <textarea name="new_committee_description" 
                                              class="form-control" 
                                              rows="3" 
                                              placeholder="Description du comité d'organisation"><?php echo e(old('new_committee_description')); ?></textarea>
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

                            
                            <?php if($currentCommittee): ?>
                                <div class="col-12 mt-4">
                                    <div class="card border-primary">
                                        <div class="card-header bg-primary text-white">
                                            <h6 class="mb-0">
                                                <i class="bi bi-people-fill"></i> Comité d'organisation actuel
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <h5 class="mb-1"><?php echo e($currentCommittee->name); ?></h5>
                                                <?php if($currentCommittee->description): ?>
                                                    <p class="text-muted small mb-2"><?php echo e($currentCommittee->description); ?></p>
                                                <?php endif; ?>
                                                <?php if($currentCommittee->host_country): ?>
                                                    <p class="mb-0">
                                                        <i class="bi bi-geo-alt"></i> 
                                                        <strong>Pays hôte :</strong> <?php echo e($currentCommittee->host_country); ?>

                                                    </p>
                                                <?php endif; ?>
                                            </div>

                                            <?php if($currentCommittee->members->count() > 0): ?>
                                                <div class="mb-3">
                                                    <h6 class="mb-2">
                                                        <i class="bi bi-person-badge"></i> 
                                                        Membres du comité (<?php echo e($currentCommittee->members->count()); ?>)
                                                    </h6>
                                                    <div class="table-responsive">
                                                        <table class="table table-sm table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th>Nom</th>
                                                                    <th>Type</th>
                                                                    <th>Rôle</th>
                                                                    <th>Service/Département</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $__currentLoopData = $currentCommittee->members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <tr>
                                                                        <td>
                                                                            <strong><?php echo e($member->user->name ?? 'N/A'); ?></strong>
                                                                            <?php if($member->user->email ?? null): ?>
                                                                                <br><small class="text-muted"><?php echo e($member->user->email); ?></small>
                                                                            <?php endif; ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php if($member->member_type === 'ceeac'): ?>
                                                                                <span class="badge bg-primary">CEEAC</span>
                                                                            <?php elseif($member->member_type === 'host_country'): ?>
                                                                                <span class="badge bg-success">Pays hôte</span>
                                                                            <?php else: ?>
                                                                                <span class="badge bg-secondary"><?php echo e($member->member_type ?? 'N/A'); ?></span>
                                                                            <?php endif; ?>
                                                                        </td>
                                                                        <td>
                                                                            <span class="badge bg-info"><?php echo e($member->role ?? 'Membre'); ?></span>
                                                                        </td>
                                                                        <td>
                                                                            <?php if($member->department || $member->service): ?>
                                                                                <?php echo e($member->department ?? ''); ?>

                                                                                <?php if($member->department && $member->service): ?> - <?php endif; ?>
                                                                                <?php echo e($member->service ?? ''); ?>

                                                                            <?php else: ?>
                                                                                <span class="text-muted">—</span>
                                                                            <?php endif; ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <div class="alert alert-warning mb-0">
                                                    <i class="bi bi-exclamation-triangle"></i> 
                                                    Aucun membre n'a encore été ajouté à ce comité.
                                                </div>
                                            <?php endif; ?>

                                            <div class="mt-3">
                                                <a href="<?php echo e(route('organization-committees.show', $currentCommittee)); ?>" 
                                                   class="btn btn-sm btn-outline-primary" target="_blank">
                                                    <i class="bi bi-eye"></i> Voir les détails complets
                                                </a>
                                                <a href="<?php echo e(route('organization-committees.edit', $currentCommittee)); ?>" 
                                                   class="btn btn-sm btn-outline-secondary" target="_blank">
                                                    <i class="bi bi-pencil"></i> Modifier le comité
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="tab-pane fade <?php echo e($activeTab === 'terms' ? 'show active' : ''); ?>" id="terms" role="tabpanel">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="bi bi-file-earmark-text text-primary"></i> Cahier des charges
                        </h5>
                        <p class="text-muted mb-4">
                            Le cahier des charges définit le partage des responsabilités et des charges financières/logistiques 
                            entre la CEEAC et le pays hôte.
                        </p>

                        <?php if($hasTerms): ?>
                            <div class="alert alert-success mb-4">
                                <i class="bi bi-check-circle"></i> 
                                <strong>Un cahier des charges existe déjà pour cette réunion.</strong>
                                <a href="<?php echo e(route('terms-of-reference.show', $meeting)); ?>" class="btn btn-sm btn-outline-primary ms-2" target="_blank">
                                    <i class="bi bi-eye"></i> Voir le cahier des charges
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info mb-4">
                                <i class="bi bi-info-circle"></i> 
                                Aucun cahier des charges n'a encore été créé pour cette réunion.
                            </div>
                        <?php endif; ?>

                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="create_terms_of_reference" 
                                           id="create_terms_of_reference" 
                                           value="1"
                                           <?php if(old('create_terms_of_reference', false) || !$hasTerms): echo 'checked'; endif; ?>>
                                    <label class="form-check-label" for="create_terms_of_reference">
                                        <?php echo e($hasTerms ? 'Créer une nouvelle version du cahier des charges' : 'Créer un cahier des charges maintenant'); ?>

                                    </label>
                                </div>
                            </div>

                            <div id="terms_fields" style="display: <?php echo e(old('create_terms_of_reference', false) || !$hasTerms ? 'block' : 'none'); ?>;">
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
                                           value="<?php echo e(old('terms_host_country', $meeting->termsOfReference?->host_country)); ?>">
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
                                    <input type="date" 
                                           name="terms_signature_date" 
                                           class="form-control" 
                                           value="<?php echo e(old('terms_signature_date', $meeting->termsOfReference?->signature_date?->format('Y-m-d'))); ?>">
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Responsabilités CEEAC</label>
                                    <textarea name="terms_responsibilities_ceeac" 
                                              class="form-control" 
                                              rows="4" 
                                              placeholder="Décrivez les responsabilités de la CEEAC..."><?php echo e(old('terms_responsibilities_ceeac', $meeting->termsOfReference?->responsibilities_ceeac)); ?></textarea>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Responsabilités pays hôte</label>
                                    <textarea name="terms_responsibilities_host" 
                                              class="form-control" 
                                              rows="4" 
                                              placeholder="Décrivez les responsabilités du pays hôte..."><?php echo e(old('terms_responsibilities_host', $meeting->termsOfReference?->responsibilities_host)); ?></textarea>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Partage financier</label>
                                    <textarea name="terms_financial_sharing" 
                                              class="form-control" 
                                              rows="3" 
                                              placeholder="Décrivez le partage des charges financières..."><?php echo e(old('terms_financial_sharing', $meeting->termsOfReference?->financial_sharing)); ?></textarea>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Partage logistique</label>
                                    <textarea name="terms_logistical_sharing" 
                                              class="form-control" 
                                              rows="3" 
                                              placeholder="Décrivez le partage des charges logistiques..."><?php echo e(old('terms_logistical_sharing', $meeting->termsOfReference?->logistical_sharing)); ?></textarea>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">
                                        <i class="bi bi-file-earmark-pdf"></i> Document physique signé (optionnel)
                                    </label>
                                    <?php if($meeting->termsOfReference && $meeting->termsOfReference->signed_document_path): ?>
                                        <div class="alert alert-info mb-2">
                                            <i class="bi bi-file-earmark-check"></i> 
                                            <strong>Document actuel :</strong> 
                                            <?php echo e($meeting->termsOfReference->signed_document_original_name); ?>

                                            <a href="<?php echo e(route('terms-of-reference.download-signed', [$meeting, $meeting->termsOfReference])); ?>" 
                                               class="btn btn-sm btn-outline-primary ms-2">
                                                <i class="bi bi-download"></i> Télécharger
                                            </a>
                                        </div>
                                    <?php endif; ?>
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

            
            <div class="tab-pane fade <?php echo e($activeTab === 'delegations' ? 'show active' : ''); ?>" id="delegations" role="tabpanel">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="bi bi-building text-primary"></i> Délégations participantes
                        </h5>
                        <p class="text-muted mb-4">
                            <strong>Important :</strong> La participation se fait par délégations institutionnelles (États membres, 
                            organisations internationales, partenaires), et non par participants individuels.
                        </p>

                        <?php
                            $entityTypes = [
                                'state_member' => 'État membre',
                                'international_organization' => 'Organisation internationale',
                                'technical_partner' => 'Partenaire technique',
                                'financial_partner' => 'Partenaire financier',
                                'other' => 'Autre'
                            ];
                            $statusColors = [
                                'invited' => 'warning',
                                'confirmed' => 'success',
                                'registered' => 'info',
                                'present' => 'primary',
                                'absent' => 'danger',
                                'excused' => 'secondary'
                            ];
                            $memberStatusColors = [
                                'invited' => 'warning',
                                'confirmed' => 'success',
                                'present' => 'primary',
                                'absent' => 'danger',
                                'excused' => 'secondary'
                            ];
                        ?>

                        <?php if($meeting->delegations->count() > 0): ?>
                            <div class="alert alert-success mb-4">
                                <i class="bi bi-check-circle"></i> 
                                <strong><?php echo e($meeting->delegations->count()); ?> délégation(s) participent à cette réunion.</strong>
                            </div>

                            
                            <div class="row g-3 mb-4">
                                <?php $__currentLoopData = $meeting->delegations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $delegation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-12">
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-header bg-light">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-0">
                                                            <i class="bi bi-building"></i> 
                                                            <strong><?php echo e($delegation->title); ?></strong>
                                                        </h6>
                                                        <?php if($delegation->country || $delegation->organization_name): ?>
                                                            <small class="text-muted">
                                                                <?php if($delegation->entity_type === 'state_member' && $delegation->country): ?>
                                                                    <i class="bi bi-geo-alt"></i> <?php echo e($delegation->country); ?>

                                                                <?php elseif($delegation->organization_name): ?>
                                                                    <i class="bi bi-building"></i> <?php echo e($delegation->organization_name); ?>

                                                                <?php endif; ?>
                                                            </small>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div>
                                                        <span class="badge bg-secondary me-2">
                                                            <?php echo e($entityTypes[$delegation->entity_type] ?? $delegation->entity_type); ?>

                                                        </span>
                                                        <span class="badge bg-<?php echo e($statusColors[$delegation->participation_status] ?? 'secondary'); ?>">
                                                            <?php echo e(ucfirst($delegation->participation_status)); ?>

                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <?php if($delegation->head_of_delegation_name): ?>
                                                    <div class="mb-3">
                                                        <strong>Chef de délégation :</strong> 
                                                        <?php echo e($delegation->head_of_delegation_name); ?>

                                                        <?php if($delegation->head_of_delegation_position): ?>
                                                            <span class="text-muted">(<?php echo e($delegation->head_of_delegation_position); ?>)</span>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if($delegation->members->count() > 0): ?>
                                                    <div class="mb-3">
                                                        <h6 class="mb-2">
                                                            <i class="bi bi-people"></i> 
                                                            Membres de la délégation (<?php echo e($delegation->members->count()); ?>)
                                                        </h6>
                                                        <div class="table-responsive">
                                                            <table class="table table-sm table-hover mb-0">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Nom</th>
                                                                        <th>Fonction</th>
                                                                        <th>Rôle</th>
                                                                        <th>Statut</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php $__currentLoopData = $delegation->members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <tr>
                                                                            <td>
                                                                                <strong><?php echo e($member->full_name); ?></strong>
                                                                                <?php if($member->isHead()): ?>
                                                                                    <span class="badge bg-primary ms-1">Chef</span>
                                                                                <?php endif; ?>
                                                                                <?php if($member->email): ?>
                                                                                    <br><small class="text-muted"><?php echo e($member->email); ?></small>
                                                                                <?php endif; ?>
                                                                            </td>
                                                                            <td>
                                                                                <?php echo e($member->position ?? '—'); ?>

                                                                                <?php if($member->title): ?>
                                                                                    <br><small class="text-muted"><?php echo e($member->title); ?></small>
                                                                                <?php endif; ?>
                                                                            </td>
                                                                            <td>
                                                                                <span class="badge bg-info">
                                                                                    <?php echo e(ucfirst($member->role)); ?>

                                                                                </span>
                                                                            </td>
                                                                            <td>
                                                                                <span class="badge bg-<?php echo e($memberStatusColors[$member->status] ?? 'secondary'); ?>">
                                                                                    <?php echo e(ucfirst($member->status)); ?>

                                                                                </span>
                                                                            </td>
                                                                        </tr>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="alert alert-warning mb-0">
                                                        <i class="bi bi-exclamation-triangle"></i> 
                                                        Aucun membre n'a encore été ajouté à cette délégation.
                                                    </div>
                                                <?php endif; ?>

                                                <div class="mt-3">
                                                    <a href="<?php echo e(route('delegations.show', $delegation)); ?>" 
                                                       class="btn btn-sm btn-outline-primary" target="_blank">
                                                        <i class="bi bi-eye"></i> Voir les détails complets
                                                    </a>
                                                    <a href="<?php echo e(route('delegations.edit', $delegation)); ?>" 
                                                       class="btn btn-sm btn-outline-secondary" target="_blank">
                                                        <i class="bi bi-pencil"></i> Modifier
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning mb-4">
                                <i class="bi bi-exclamation-triangle"></i> 
                                Aucune délégation n'a encore été ajoutée à cette réunion.
                            </div>
                        <?php endif; ?>

                        <div class="row g-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Gérer les délégations</h6>
                                    <a href="<?php echo e(route('delegations.create', ['meeting_id' => $meeting->id])); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-plus-circle"></i> Ajouter une délégation
                                    </a>
                                </div>
                                <div class="form-text mt-2">
                                    Les délégations peuvent être ajoutées et gérées depuis la page de détails de la réunion.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="d-flex justify-content-between align-items-center mt-4">
            <a href="<?php echo e(route('meetings.show', $meeting)); ?>" class="btn btn-outline-secondary">
                <i class="bi bi-x-circle"></i> Annuler
            </a>
            <div>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Enregistrer les modifications
                </button>
            </div>
        </div>
    </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('meetingForm');
    
    // Gestion de l'affichage du formulaire de comité
    const noCommittee = document.getElementById('no_committee');
    const useExisting = document.getElementById('use_existing_committee');
    const createNew = document.getElementById('create_new_committee');
    const existingSection = document.getElementById('existing_committee_section');
    const newSection = document.getElementById('new_committee_section');

    function updateCommitteeSections() {
        if (noCommittee && noCommittee.checked) {
            if (existingSection) existingSection.style.display = 'none';
            if (newSection) newSection.style.display = 'none';
        } else if (useExisting && useExisting.checked) {
            if (existingSection) existingSection.style.display = 'block';
            if (newSection) newSection.style.display = 'none';
        } else if (createNew && createNew.checked) {
            if (existingSection) existingSection.style.display = 'none';
            if (newSection) newSection.style.display = 'block';
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

    if (createTermsCheckbox && termsFields) {
        createTermsCheckbox.addEventListener('change', function() {
            if (this.checked) {
                termsFields.style.display = 'block';
                const hostCountryInput = document.querySelector('input[name="terms_host_country"]');
                if (hostCountryInput && !hostCountryInput.value.trim()) {
                    hostCountryInput.focus();
                }
            } else {
                termsFields.style.display = 'none';
            }
        });
    }

    // Validation des onglets avant soumission
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
            const committeeSelect = document.querySelector('select[name="organization_committee_id"]');
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
            const existingAlerts = document.querySelectorAll('.alert-danger');
            existingAlerts.forEach(alert => alert.remove());
            
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger alert-dismissible fade show';
            alertDiv.innerHTML = `
                <strong>Erreur de validation :</strong> ${errorMessage}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            form.insertBefore(alertDiv, form.firstChild);
            
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
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mise à jour en cours...';
            submitBtn.disabled = true;
        }
    });

    // Enregistrer l'onglet actif avant soumission
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
    
    // Initialiser l'onglet actif au chargement
    const activeTabFromSession = '<?php echo e($activeTab); ?>';
    if (activeTabFromSession && activeTabFromSession !== 'general') {
        const tabButton = document.querySelector(`#${activeTabFromSession}-tab`);
        if (tabButton) {
            const tab = new bootstrap.Tab(tabButton);
            tab.show();
        }
    }

    // Afficher les messages de succès de session s'ils existent
    <?php if(session('success')): ?>
        const existingSuccessAlerts = document.querySelectorAll('.alert-success');
        existingSuccessAlerts.forEach(alert => {
            if (!alert.classList.contains('alert-dismissible')) {
                alert.remove();
            }
        });
        
        const successAlert = document.createElement('div');
        successAlert.className = 'alert alert-success alert-dismissible fade show';
        successAlert.innerHTML = `
            <i class="bi bi-check-circle"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        form.insertBefore(successAlert, form.firstChild);
        
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



<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views\meetings\edit.blade.php ENDPATH**/ ?>