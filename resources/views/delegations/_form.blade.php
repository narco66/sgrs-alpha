@csrf

{{-- Informations générales de la délégation --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="bi bi-info-circle text-primary"></i> Informations générales de la délégation
        </h5>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Titre de la délégation <span class="text-danger">*</span></label>
            <input type="text" 
                   name="title" 
                   id="title_input"
                   class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title', $delegation->title ?? '') }}" 
                   required
                   placeholder="Ex: Délégation de la République du Congo">
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Type d'entité <span class="text-danger">*</span></label>
                <select name="entity_type" 
                        class="form-select @error('entity_type') is-invalid @enderror" 
                        required
                        id="entity_type_select">
                    <option value="">Sélectionner un type</option>
                    <option value="state_member" @selected(old('entity_type', $delegation->entity_type ?? '') == 'state_member')>
                        État membre
                    </option>
                    <option value="international_organization" @selected(old('entity_type', $delegation->entity_type ?? '') == 'international_organization')>
                        Organisation internationale
                    </option>
                    <option value="technical_partner" @selected(old('entity_type', $delegation->entity_type ?? '') == 'technical_partner')>
                        Partenaire technique
                    </option>
                    <option value="financial_partner" @selected(old('entity_type', $delegation->entity_type ?? '') == 'financial_partner')>
                        Partenaire financier
                    </option>
                    <option value="other" @selected(old('entity_type', $delegation->entity_type ?? '') == 'other')>
                        Autre
                    </option>
                </select>
                @error('entity_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6" id="country_field_wrapper">
                <label class="form-label">Pays <span class="text-danger" id="country_required_span">*</span></label>
                <input type="text" 
                       name="country" 
                       id="country_input"
                       class="form-control @error('country') is-invalid @enderror"
                       value="{{ old('country', $delegation->country ?? '') }}"
                       placeholder="Ex: République du Congo">
                @error('country')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6" id="organization_field_wrapper" style="display: none;">
                <label class="form-label">Nom de l'organisation <span class="text-danger" id="org_required_span">*</span></label>
                <input type="text" 
                       name="organization_name" 
                       id="organization_name_input"
                       class="form-control @error('organization_name') is-invalid @enderror"
                       value="{{ old('organization_name', $delegation->organization_name ?? '') }}"
                       placeholder="Ex: Union Africaine">
                @error('organization_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Réunion associée <span class="text-danger">*</span></label>
            <select name="meeting_id" 
                    class="form-select @error('meeting_id') is-invalid @enderror" 
                    required
                    id="meeting_id_select">
                <option value="">Sélectionner une réunion</option>
                @foreach($meetings ?? [] as $meeting)
                    <option value="{{ $meeting->id }}" 
                            @selected(old('meeting_id', $delegation->meeting_id ?? $meetingId ?? request('meeting_id')) == $meeting->id)>
                        {{ $meeting->title }} - {{ $meeting->start_at?->format('d/m/Y') }}
                    </option>
                @endforeach
            </select>
            @error('meeting_id')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            @if(request('meeting_id') && !old('meeting_id'))
                <div class="form-text text-info">
                    <i class="bi bi-info-circle"></i> Réunion pré-sélectionnée depuis la page de la réunion.
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" 
                      rows="3" 
                      class="form-control @error('description') is-invalid @enderror"
                      placeholder="Description de la délégation...">{{ old('description', $delegation->description ?? '') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Chef de délégation (nom)</label>
                <input type="text" 
                       name="head_of_delegation_name" 
                       class="form-control @error('head_of_delegation_name') is-invalid @enderror"
                       value="{{ old('head_of_delegation_name', $delegation->head_of_delegation_name ?? '') }}"
                       placeholder="Ex: Jean KABILA">
                @error('head_of_delegation_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Fonction du chef</label>
                <input type="text" 
                       name="head_of_delegation_position" 
                       class="form-control @error('head_of_delegation_position') is-invalid @enderror"
                       value="{{ old('head_of_delegation_position', $delegation->head_of_delegation_position ?? '') }}"
                       placeholder="Ex: Ministre des Affaires Étrangères">
                @error('head_of_delegation_position')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Email du chef</label>
                <input type="email" 
                       name="head_of_delegation_email" 
                       class="form-control @error('head_of_delegation_email') is-invalid @enderror"
                       value="{{ old('head_of_delegation_email', $delegation->head_of_delegation_email ?? '') }}">
                @error('head_of_delegation_email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <label class="form-label">Email de contact</label>
                <input type="email" 
                       name="contact_email" 
                       class="form-control @error('contact_email') is-invalid @enderror"
                       value="{{ old('contact_email', $delegation->contact_email ?? '') }}">
                @error('contact_email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Téléphone de contact</label>
                <input type="text" 
                       name="contact_phone" 
                       class="form-control @error('contact_phone') is-invalid @enderror"
                       value="{{ old('contact_phone', $delegation->contact_phone ?? '') }}">
                @error('contact_phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row g-3 mt-2">
            <div class="col-md-6">
            <label class="form-label">Statut de participation</label>
            <select name="participation_status" 
                    class="form-select @error('participation_status') is-invalid @enderror">
                <option value="invited" @selected(old('participation_status', $delegation->participation_status ?? 'invited') == 'invited')>
                    Invité
                </option>
                <option value="confirmed" @selected(old('participation_status', $delegation->participation_status ?? '') == 'confirmed')>
                    Confirmé
                </option>
                <option value="registered" @selected(old('participation_status', $delegation->participation_status ?? '') == 'registered')>
                    Inscrit
                </option>
                <option value="present" @selected(old('participation_status', $delegation->participation_status ?? '') == 'present')>
                    Présent
                </option>
                <option value="absent" @selected(old('participation_status', $delegation->participation_status ?? '') == 'absent')>
                    Absent
                </option>
                <option value="excused" @selected(old('participation_status', $delegation->participation_status ?? '') == 'excused')>
                    Excusé
                </option>
            </select>
            @error('participation_status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            </div>
        </div>

        <div class="mb-3 mt-3">
            <label class="form-label">Notes</label>
            <textarea name="notes" 
                      rows="2" 
                      class="form-control @error('notes') is-invalid @enderror"
                      placeholder="Notes additionnelles...">{{ old('notes', $delegation->notes ?? '') }}</textarea>
            @error('notes')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

{{-- Membres de la délégation --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="bi bi-people text-primary"></i> Membres de la délégation <span class="text-muted small">(optionnel)</span>
        </h5>
        <button type="button" class="btn btn-sm btn-primary" id="add_member_btn">
            <i class="bi bi-plus-circle"></i> Ajouter un membre
        </button>
    </div>
    <div class="card-body">
        <div class="alert alert-info mb-3" id="no_members_alert">
            <i class="bi bi-info-circle"></i> 
            <strong>Note :</strong> Vous pouvez créer la délégation maintenant et ajouter les membres plus tard. 
            Les membres sont optionnels lors de la création.
        </div>
        
        <div id="members_container">
            @php
                $oldMembers = old('members', []);
                $existingMembers = isset($delegation) && $delegation->members ? $delegation->members->toArray() : [];
                $members = !empty($oldMembers) ? $oldMembers : $existingMembers;
            @endphp

            @if(!empty($members))
                @foreach($members as $index => $member)
                    <div class="member-row-wrapper">
                    @include('delegations._member_form', [
                        'index' => $index,
                        'member' => $member,
                        'isExisting' => isset($member['id'])
                    ])
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

{{-- Template caché pour nouveaux membres --}}
<template id="member_template">
    <div class="member-row border rounded p-3 mb-3" data-member-index="__INDEX__">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0">
                <i class="bi bi-person"></i> 
                Membre <span class="member-number"></span>
            </h6>
            <button type="button" class="btn btn-sm btn-outline-danger remove-member-btn">
                <i class="bi bi-trash"></i> Supprimer
            </button>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Prénom <span class="text-danger">*</span></label>
                <input type="text" name="members[__INDEX__][first_name]" class="form-control member-first-name" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nom <span class="text-danger">*</span></label>
                <input type="text" name="members[__INDEX__][last_name]" class="form-control member-last-name" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" name="members[__INDEX__][email]" class="form-control member-email" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Téléphone</label>
                <input type="text" name="members[__INDEX__][phone]" class="form-control" placeholder="+242 06 123 456 78">
            </div>
            <div class="col-md-6">
                <label class="form-label">Fonction / Position</label>
                <input type="text" name="members[__INDEX__][position]" class="form-control" placeholder="Ex: Ministre, Ambassadeur">
            </div>
            <div class="col-md-6">
                <label class="form-label">Titre / Grade</label>
                <input type="text" name="members[__INDEX__][title]" class="form-control" placeholder="Ex: Son Excellence, Dr.">
            </div>
            <div class="col-md-6">
                <label class="form-label">Institution</label>
                <input type="text" name="members[__INDEX__][institution]" class="form-control" placeholder="Ex: Ministère des Affaires Étrangères">
            </div>
            <div class="col-md-6">
                <label class="form-label">Département / Service</label>
                <input type="text" name="members[__INDEX__][department]" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">Rôle dans la délégation <span class="text-danger">*</span></label>
                <select name="members[__INDEX__][role]" class="form-select member-role" required>
                    <option value="">Sélectionner un rôle</option>
                    <option value="head">Chef de délégation</option>
                    <option value="member" selected>Membre</option>
                    <option value="expert">Expert</option>
                    <option value="observer">Observateur</option>
                    <option value="secretary">Secrétaire</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Statut</label>
                <select name="members[__INDEX__][status]" class="form-select">
                    <option value="invited" selected>Invitié</option>
                    <option value="confirmed">Confirmé</option>
                    <option value="present">Présent</option>
                    <option value="absent">Absent</option>
                    <option value="excused">Excusé</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Notes</label>
                <textarea name="members[__INDEX__][notes]" rows="2" class="form-control" placeholder="Notes additionnelles..."></textarea>
            </div>
        </div>
    </div>
</template>

@push('scripts')
<script>
(function() {
    'use strict';
    
    // Variables globales
    let memberIndex = {{ !empty($members) ? count($members) : 0 }};
    let formModified = false;
    let isSubmitting = false;
    
    // Fonction utilitaire pour afficher des notifications
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3`;
        toast.style.zIndex = '9999';
        toast.style.minWidth = '350px';
        toast.style.maxWidth = '500px';
        toast.innerHTML = `
            <i class="bi ${type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            if (toast.parentNode) {
                toast.style.opacity = '0';
                toast.style.transition = 'opacity 0.5s';
                setTimeout(() => toast.remove(), 500);
            }
        }, type === 'success' ? 5000 : 8000);
    }
    
    // Initialisation
    function init() {
        const form = document.querySelector('form[action*="delegations"]');
        if (!form) {
            console.error('Formulaire non trouvé');
            return;
        }
        
        // Gestion du type d'entité
        const entityTypeSelect = document.getElementById('entity_type_select');
        const countryWrapper = document.getElementById('country_field_wrapper');
        const orgWrapper = document.getElementById('organization_field_wrapper');
        const countryInput = document.getElementById('country_input');
        const orgInput = document.getElementById('organization_name_input');
        
        function updateEntityFields() {
            const value = entityTypeSelect.value;
        
        if (value === 'state_member' || value === 'other') {
                countryWrapper.style.display = 'block';
                orgWrapper.style.display = 'none';
            if (countryInput) {
                countryInput.required = true;
                countryInput.removeAttribute('disabled');
            }
            if (orgInput) {
                orgInput.required = false;
                orgInput.value = '';
                orgInput.setAttribute('disabled', 'disabled');
            }
        } else if (['international_organization', 'technical_partner', 'financial_partner'].includes(value)) {
                countryWrapper.style.display = 'none';
                orgWrapper.style.display = 'block';
            if (countryInput) {
                countryInput.required = false;
                countryInput.value = '';
                countryInput.setAttribute('disabled', 'disabled');
            }
            if (orgInput) {
                orgInput.required = true;
                orgInput.removeAttribute('disabled');
            }
        } else {
                countryWrapper.style.display = 'none';
                orgWrapper.style.display = 'none';
            if (countryInput) {
                countryInput.required = false;
                countryInput.setAttribute('disabled', 'disabled');
            }
            if (orgInput) {
                orgInput.required = false;
                orgInput.setAttribute('disabled', 'disabled');
            }
            }
    }
    
    if (entityTypeSelect) {
            entityTypeSelect.addEventListener('change', updateEntityFields);
            updateEntityFields();
        }
        
        // Gestion de l'ajout de membres
        const addMemberBtn = document.getElementById('add_member_btn');
        const membersContainer = document.getElementById('members_container');
        const memberTemplate = document.getElementById('member_template');
        const noMembersAlert = document.getElementById('no_members_alert');
    
    if (addMemberBtn && membersContainer && memberTemplate) {
        addMemberBtn.addEventListener('click', function() {
                if (noMembersAlert) noMembersAlert.remove();
                
                const templateContent = memberTemplate.innerHTML;
                const newMemberHTML = templateContent.replace(/__INDEX__/g, memberIndex);
                
                const memberDiv = document.createElement('div');
                memberDiv.innerHTML = newMemberHTML;
                memberDiv.querySelector('.member-row').setAttribute('data-member-index', memberIndex);
                
                const memberNumber = memberDiv.querySelector('.member-number');
                if (memberNumber) {
                    memberNumber.textContent = memberIndex + 1;
                }
                
                membersContainer.appendChild(memberDiv);
                
                // Animation
                const newRow = memberDiv.querySelector('.member-row');
                newRow.style.opacity = '0';
                newRow.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    newRow.style.transition = 'all 0.3s ease';
                    newRow.style.opacity = '1';
                    newRow.style.transform = 'translateY(0)';
                }, 10);
                
                // Focus sur le premier champ
                const firstInput = memberDiv.querySelector('.member-first-name');
                if (firstInput) {
                    setTimeout(() => firstInput.focus(), 300);
                }
                
                memberIndex++;
                showToast('✓ Membre ajouté avec succès', 'success');
            });
        }
        
        // Gestion de la suppression de membres
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-member-btn')) {
                const btn = e.target.closest('.remove-member-btn');
                const memberRow = btn.closest('.member-row');
                
                if (memberRow) {
                    const firstName = memberRow.querySelector('.member-first-name')?.value || '';
                    const lastName = memberRow.querySelector('.member-last-name')?.value || '';
                    const memberName = (firstName || lastName) ? `${firstName} ${lastName}`.trim() : 'ce membre';
                    
                    if (confirm(`Êtes-vous sûr de vouloir supprimer ${memberName} ?`)) {
                        memberRow.style.transition = 'all 0.3s ease';
                        memberRow.style.opacity = '0';
                        memberRow.style.transform = 'translateX(-20px)';
                        
                        setTimeout(() => {
                            memberRow.remove();
                            
                            if (membersContainer.querySelectorAll('.member-item, .member-row').length === 0 && !noMembersAlert) {
                                membersContainer.innerHTML = '<div class="alert alert-info" id="no_members_alert"><i class="bi bi-info-circle"></i> Aucun membre ajouté. Cliquez sur "Ajouter un membre" pour commencer.</div>';
                            }
                            
                            showToast('✓ Membre supprimé avec succès', 'success');
                        }, 300);
                    }
                }
            }
        });
        
        // Détection des modifications
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('change', () => formModified = true);
            input.addEventListener('input', () => formModified = true);
        });
        
        // Protection avant navigation
        window.addEventListener('beforeunload', function(e) {
            if (formModified && !isSubmitting) {
                e.preventDefault();
                e.returnValue = 'Vous avez des modifications non enregistrées. Êtes-vous sûr de vouloir quitter ?';
                return e.returnValue;
            }
        });
        
        // Gestion du bouton annuler
        const cancelBtn = document.getElementById('cancelBtn');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function(e) {
                if (formModified && !confirm('Vous avez des modifications non enregistrées. Quitter quand même ?')) {
                    e.preventDefault();
                return false;
            }
        };
        
        // VALIDATION ET SOUMISSION DU FORMULAIRE
        const submitBtn = document.getElementById('submitBtn');
        
        // Créer une référence nommée pour pouvoir retirer le listener
        let submitHandlerReference = null;
        
        submitHandlerReference = function(e) {
            console.log('🚀 Événement submit capturé');
            
            // Vérifier si on est déjà en train de soumettre (évite la boucle infinie)
            if (form.getAttribute('data-submitting') === 'true') {
                console.log('✅ Soumission déjà en cours, laisser passer...');
                // Ne pas bloquer - laisser la soumission native se faire
                return true;
            }
            
            // Si déjà validé, laisser passer
            if (form.getAttribute('data-validated') === 'true') {
                console.log('✅ Formulaire déjà validé, soumission autorisée - NE PAS BLOQUER');
                form.removeAttribute('data-validated');
                form.setAttribute('data-submitting', 'true'); // Marquer comme en cours de soumission
                // Ne pas appeler preventDefault() - laisser la soumission se faire naturellement
                return true;
            }
            
            // Bloquer pour validation
            console.log('⏸️ Blocage de la soumission pour validation...');
            e.preventDefault();
            e.stopPropagation();
            
            console.log('🔍 Validation du formulaire...');
            showToast('<i class="bi bi-hourglass-split me-2"></i>Validation en cours...', 'success');
            
            let isValid = true;
            let errors = [];
            
            // Validation titre
            const title = document.getElementById('title_input');
            if (!title || !title.value.trim()) {
                isValid = false;
                errors.push('Le titre de la délégation est obligatoire');
                if (title) {
                    title.classList.add('is-invalid');
                    title.focus();
                }
            } else {
                if (title) title.classList.remove('is-invalid');
            }
            
            // Validation type d'entité
            const entityType = entityTypeSelect;
            if (!entityType || !entityType.value) {
                isValid = false;
                errors.push('Le type d\'entité est obligatoire');
                if (entityType) {
                    entityType.classList.add('is-invalid');
                    if (isValid) entityType.focus();
                }
            } else {
                if (entityType) entityType.classList.remove('is-invalid');
                
                // Validation champs conditionnels
                const entityValue = entityType.value;
                if (['state_member', 'other'].includes(entityValue)) {
                    if (!countryInput || !countryInput.value.trim()) {
                        isValid = false;
                        errors.push('Le pays est obligatoire pour ce type d\'entité');
                        if (countryInput && isValid) {
                            countryInput.classList.add('is-invalid');
                            countryInput.focus();
                        }
                    } else {
                        if (countryInput) countryInput.classList.remove('is-invalid');
                    }
                } else if (['international_organization', 'technical_partner', 'financial_partner'].includes(entityValue)) {
                    if (!orgInput || !orgInput.value.trim()) {
                        isValid = false;
                        errors.push('Le nom de l\'organisation est obligatoire pour ce type d\'entité');
                        if (orgInput && isValid) {
                            orgInput.classList.add('is-invalid');
                            orgInput.focus();
                        }
                    } else {
                        if (orgInput) orgInput.classList.remove('is-invalid');
                    }
                }
            }
            
            // Validation réunion
            const meetingId = document.getElementById('meeting_id_select');
            if (!meetingId || !meetingId.value) {
                isValid = false;
                errors.push('La réunion associée est obligatoire');
                if (meetingId) {
                    meetingId.classList.add('is-invalid');
                    if (isValid) meetingId.focus();
                }
            } else {
                if (meetingId) meetingId.classList.remove('is-invalid');
            }
            
            // Validation membres
            const memberRows = membersContainer.querySelectorAll('.member-item, .member-row');
            memberRows.forEach((row, index) => {
                const firstName = row.querySelector('.member-first-name') || row.querySelector('input[name*="[first_name]"]');
                const lastName = row.querySelector('.member-last-name') || row.querySelector('input[name*="[last_name]"]');
                const email = row.querySelector('.member-email') || row.querySelector('input[name*="[email]"]');
                const role = row.querySelector('.member-role') || row.querySelector('select[name*="[role]"]');
                
                const fnVal = firstName ? firstName.value.trim() : '';
                const lnVal = lastName ? lastName.value.trim() : '';
                const emVal = email ? email.value.trim() : '';
                const roleVal = role ? role.value : '';
                
                if (fnVal || lnVal || emVal) {
                    if (!fnVal) {
                        isValid = false;
                        errors.push(`Prénom du membre ${index + 1} requis`);
                        if (firstName) firstName.classList.add('is-invalid');
                    } else if (firstName) firstName.classList.remove('is-invalid');
                    
                    if (!lnVal) {
                        isValid = false;
                        errors.push(`Nom du membre ${index + 1} requis`);
                        if (lastName) lastName.classList.add('is-invalid');
                    } else if (lastName) lastName.classList.remove('is-invalid');
                    
                    if (!emVal) {
                        isValid = false;
                        errors.push(`Email du membre ${index + 1} requis`);
                        if (email) email.classList.add('is-invalid');
                    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emVal)) {
                        isValid = false;
                        errors.push(`Email du membre ${index + 1} invalide`);
                        if (email) email.classList.add('is-invalid');
                    } else if (email) email.classList.remove('is-invalid');
                    
                    if (!roleVal) {
                        isValid = false;
                        errors.push(`Rôle du membre ${index + 1} requis`);
                        if (role) role.classList.add('is-invalid');
                    } else if (role) role.classList.remove('is-invalid');
                }
            });
            
            if (!isValid) {
                const errorMsg = '<strong>Erreurs détectées :</strong><ul class="mb-0 mt-2"><li>' + errors.join('</li><li>') + '</li></ul>';
                showToast(errorMsg, 'error');
                
                // Scroll vers la première erreur
                const firstError = form.querySelector('.is-invalid');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    setTimeout(() => firstError.focus(), 500);
                }
                
                return false;
            }
            
            // Validation OK - Confirmation
            const membersCount = memberRows.length;
            const meetingText = meetingId ? meetingId.options[meetingId.selectedIndex].text : 'Non sélectionnée';
            
            let confirmMsg = '✓ Validation réussie !\n\n';
            confirmMsg += 'Confirmer l\'enregistrement avec :\n\n';
            confirmMsg += '📋 Titre : ' + (title ? title.value : '') + '\n';
            confirmMsg += '🏛️ Type : ' + (entityType ? entityType.options[entityType.selectedIndex].text : '') + '\n';
            confirmMsg += '📅 Réunion : ' + meetingText + '\n';
            confirmMsg += '👥 Membres : ' + membersCount + (membersCount > 1 ? ' membres' : (membersCount === 1 ? ' membre' : ' aucun'));
            confirmMsg += '\n\nCliquer sur OK pour enregistrer.';
            
            if (!confirm(confirmMsg)) {
                showToast('ℹ️ Enregistrement annulé', 'success');
                return false;
            }
            
            // Confirmation reçue - SOUMETTRE
            console.log('✅ Confirmation reçue, soumission...');
            isSubmitting = true;
            formModified = false;
            
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enregistrement...';
            }
            
            showToast('<i class="bi bi-cloud-upload me-2"></i>Envoi des données au serveur...', 'success');
            
            // Réactiver les champs si nécessaire AVANT la soumission
            form.querySelectorAll('input[disabled], select[disabled], textarea[disabled]').forEach(input => {
                if (input.name !== '_token') {
                    input.disabled = false;
                }
            });
            
            // Vérifier que le token CSRF est présent
            const csrfToken = form.querySelector('input[name="_token"]');
            if (!csrfToken) {
                showToast('❌ Erreur: Token de sécurité manquant. Veuillez rafraîchir la page.', 'error');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-check-circle"></i> Enregistrer la délégation';
                }
                isSubmitting = false;
                return false;
            }
            
            console.log('📤 Préparation de la soumission...');
            console.log('Action du formulaire:', form.action);
            console.log('Méthode:', form.method);
            console.log('CSRF Token présent:', !!csrfToken);
            
            // Marquer comme validé pour éviter la re-validation
            form.setAttribute('data-validated', 'true');
            
            // Créer un événement submit natif et le déclencher
            const submitEvent = new Event('submit', {
                bubbles: true,
                cancelable: true
            });
            
            // Vérifier que tous les champs nécessaires sont présents
            console.log('📋 Vérification finale des données...');
            const formData = new FormData(form);
            console.log('✅ Titre:', formData.get('title'));
            console.log('✅ Type entité:', formData.get('entity_type'));
            console.log('✅ Réunion ID:', formData.get('meeting_id'));
            console.log('✅ Redirect to meeting:', formData.get('redirect_to_meeting'));
            console.log('✅ CSRF Token:', formData.get('_token') ? 'Présent' : 'MANQUANT!');
            
            // CRITIQUE: Retirer le listener AVANT de soumettre pour éviter la boucle infinie
            console.log('🔧 Retrait du listener submit pour éviter la boucle...');
            if (submitHandlerReference) {
                form.removeEventListener('submit', submitHandlerReference);
                console.log('✅ Listener retiré avec succès');
            }
            
            // Réactiver tous les champs désactivés
            form.querySelectorAll('input[disabled], select[disabled], textarea[disabled]').forEach(input => {
                if (input.name !== '_token') {
                    input.disabled = false;
                }
            });
            
            // Réactiver le bouton (nécessaire pour requestSubmit)
            if (submitBtn) {
                submitBtn.disabled = false;
            }
            
            // Marquer comme validé ET en cours de soumission AVANT la soumission
            form.setAttribute('data-validated', 'true');
            form.setAttribute('data-submitting', 'true');
            console.log('✅ Formulaire marqué comme validé et en cours de soumission');
            
            // Retirer le listener MAINTENANT (après avoir marqué)
            console.log('🔧 Retrait du listener submit...');
            if (submitHandlerReference) {
                form.removeEventListener('submit', submitHandlerReference);
                console.log('✅ Listener retiré');
            }
            
            // Utiliser requestSubmit() si disponible (la meilleure méthode moderne)
            if (typeof HTMLFormElement.prototype.requestSubmit !== 'undefined') {
                console.log('📤 Utilisation de requestSubmit()...');
                try {
                    // Soumettre immédiatement - le listener est retiré et le flag est activé
                    form.requestSubmit(submitBtn);
                    console.log('✅ requestSubmit() appelé - Navigation en cours...');
                    // Ne rien faire après - laisser la navigation se faire
                    return;
                } catch (err) {
                    console.error('❌ Erreur avec requestSubmit:', err);
                    // En cas d'erreur, utiliser la soumission native
                    setTimeout(() => {
                        console.log('📤 Fallback: Soumission native...');
                        form.submit();
                    }, 100);
                    return;
                }
            }
            
            // Fallback pour navigateurs anciens: soumettre directement
            console.log('📤 Méthode fallback: Soumission directe...');
            setTimeout(() => {
                form.submit();
            }, 100);
        };
        
        // Ajouter le listener après avoir créé la fonction
        form.addEventListener('submit', submitHandlerReference);
        
        // Afficher messages de session après chargement
        setTimeout(function() {
            @if(session('success'))
                showToast(@json(session('success')), 'success');
                console.log('✅ Message de succès affiché:', @json(session('success')));
            @endif
            
            @if(session('error'))
                showToast(@json(session('error')), 'error');
                console.log('❌ Message d\'erreur affiché:', @json(session('error')));
            @endif
            
            // Afficher les erreurs de validation si présentes
            @if($errors->any())
                const validationErrors = @json($errors->all());
                console.log('⚠️ Erreurs de validation:', validationErrors);
                let errorMsg = '<strong>Erreurs de validation :</strong><ul class="mb-0 mt-2"><li>' + validationErrors.join('</li><li>') + '</li></ul>';
                showToast(errorMsg, 'error');
            @endif
        }, 500);
    }
    
    // Lancer l'initialisation
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
@endpush
