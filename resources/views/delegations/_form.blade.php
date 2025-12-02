@csrf

{{-- Informations générales de la délégation --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="bi bi-info-circle"></i> Informations générales
        </h5>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Titre de la délégation <span class="text-danger">*</span></label>
            <input type="text" 
                   name="title" 
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
                        id="entity_type">
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

            <div class="col-md-6" id="country_fields" style="display: {{ in_array(old('entity_type', $delegation->entity_type ?? ''), ['state_member', 'other']) ? 'block' : 'none' }};">
                <label class="form-label">Pays <span class="text-danger" id="country_required" style="display: {{ in_array(old('entity_type', $delegation->entity_type ?? ''), ['state_member', 'other']) ? 'inline' : 'none' }};">*</span></label>
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

            <div class="col-md-6" id="organization_fields" style="display: {{ in_array(old('entity_type', $delegation->entity_type ?? ''), ['international_organization', 'technical_partner', 'financial_partner']) ? 'block' : 'none' }};">
                <label class="form-label">Nom de l'organisation <span class="text-danger" id="org_required" style="display: {{ in_array(old('entity_type', $delegation->entity_type ?? ''), ['international_organization', 'technical_partner', 'financial_partner']) ? 'inline' : 'none' }};">*</span></label>
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

        <div class="row g-3">
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

        <div class="mb-3">
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

        <div class="mb-3">
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
            <i class="bi bi-people"></i> Membres de la délégation <span class="text-muted small">(optionnel)</span>
        </h5>
        <button type="button" class="btn btn-sm btn-primary" id="addMemberBtn">
            <i class="bi bi-plus-circle"></i> Ajouter un membre
        </button>
    </div>
    <div class="card-body">
        <div class="alert alert-info mb-3">
            <i class="bi bi-info-circle"></i> 
            <strong>Note :</strong> Vous pouvez créer la délégation maintenant et ajouter les membres plus tard. 
            Les membres sont optionnels lors de la création.
        </div>
        
        <div id="membersContainer">
            @php
                $oldMembers = old('members', []);
                $existingMembers = isset($delegation) && $delegation->members ? $delegation->members->toArray() : [];
                $members = !empty($oldMembers) ? $oldMembers : $existingMembers;
            @endphp

            @if(empty($members))
                <div class="alert alert-light border">
                    <i class="bi bi-info-circle"></i> 
                    Aucun membre ajouté pour le moment. Cliquez sur "Ajouter un membre" pour commencer, 
                    ou enregistrez la délégation et ajoutez les membres plus tard.
                </div>
            @else
                @foreach($members as $index => $member)
                    @include('delegations._member_form', [
                        'index' => $index,
                        'member' => $member,
                        'isExisting' => isset($member['id'])
                    ])
                @endforeach
            @endif
        </div>
    </div>
</div>

{{-- Template pour un nouveau membre (caché) --}}
<div id="memberTemplate" style="display: none;">
    <div class="member-item border rounded p-3 mb-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0">
                <i class="bi bi-person"></i> 
                Membre <span class="member-index"></span>
            </h6>
            <button type="button" class="btn btn-sm btn-outline-danger remove-member-btn">
                <i class="bi bi-trash"></i> Supprimer
            </button>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Prénom <span class="text-danger">*</span></label>
                <input type="text" name="members[__INDEX__][first_name]" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nom <span class="text-danger">*</span></label>
                <input type="text" name="members[__INDEX__][last_name]" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" name="members[__INDEX__][email]" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Téléphone</label>
                <input type="text" name="members[__INDEX__][phone]" class="form-control" placeholder="+242 06 123 456 78">
            </div>
            <div class="col-md-6">
                <label class="form-label">Fonction / Position</label>
                <input type="text" name="members[__INDEX__][position]" class="form-control" placeholder="Ex: Ministre, Ambassadeur, Conseiller">
            </div>
            <div class="col-md-6">
                <label class="form-label">Titre / Grade</label>
                <input type="text" name="members[__INDEX__][title]" class="form-control" placeholder="Ex: Son Excellence, Dr., Prof.">
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
                <select name="members[__INDEX__][role]" class="form-select" required>
                    <option value="">Sélectionner un rôle</option>
                    <option value="head">Chef de délégation</option>
                    <option value="deputy">Adjoint</option>
                    <option value="member" selected>Membre</option>
                    <option value="advisor">Conseiller</option>
                    <option value="expert">Expert</option>
                    <option value="interpreter">Interprète</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Statut</label>
                <select name="members[__INDEX__][status]" class="form-select">
                    <option value="pending" selected>En attente</option>
                    <option value="confirmed">Confirmé</option>
                    <option value="registered">Inscrit</option>
                    <option value="present">Présent</option>
                    <option value="absent">Absent</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Notes</label>
                <textarea name="members[__INDEX__][notes]" rows="2" class="form-control" placeholder="Notes additionnelles sur ce membre..."></textarea>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let memberIndex = {{ !empty($members) ? count($members) : 0 }};
    
    // Gestion de l'affichage des champs selon le type d'entité
    const entityTypeSelect = document.getElementById('entity_type');
    const countryFields = document.getElementById('country_fields');
    const organizationFields = document.getElementById('organization_fields');
    
    function updateEntityTypeFields() {
        if (!entityTypeSelect) return;
        
        const value = entityTypeSelect.value;
        const countryInput = document.getElementById('country_input');
        const orgInput = document.getElementById('organization_name_input');
        const countryRequired = document.getElementById('country_required');
        const orgRequired = document.getElementById('org_required');
        
        if (value === 'state_member' || value === 'other') {
            if (countryFields) countryFields.style.display = 'block';
            if (organizationFields) organizationFields.style.display = 'none';
            if (countryInput) {
                countryInput.required = true;
                countryInput.removeAttribute('disabled');
            }
            if (orgInput) {
                orgInput.required = false;
                orgInput.value = '';
                orgInput.setAttribute('disabled', 'disabled');
            }
            if (countryRequired) countryRequired.style.display = 'inline';
            if (orgRequired) orgRequired.style.display = 'none';
        } else if (['international_organization', 'technical_partner', 'financial_partner'].includes(value)) {
            if (countryFields) countryFields.style.display = 'none';
            if (organizationFields) organizationFields.style.display = 'block';
            if (countryInput) {
                countryInput.required = false;
                countryInput.value = '';
                countryInput.setAttribute('disabled', 'disabled');
            }
            if (orgInput) {
                orgInput.required = true;
                orgInput.removeAttribute('disabled');
            }
            if (countryRequired) countryRequired.style.display = 'none';
            if (orgRequired) orgRequired.style.display = 'inline';
        } else {
            if (countryFields) countryFields.style.display = 'none';
            if (organizationFields) organizationFields.style.display = 'none';
            if (countryInput) {
                countryInput.required = false;
                countryInput.setAttribute('disabled', 'disabled');
            }
            if (orgInput) {
                orgInput.required = false;
                orgInput.setAttribute('disabled', 'disabled');
            }
            if (countryRequired) countryRequired.style.display = 'none';
            if (orgRequired) orgRequired.style.display = 'none';
        }
    }
    
    if (entityTypeSelect) {
        entityTypeSelect.addEventListener('change', updateEntityTypeFields);
        // Déclencher l'événement au chargement pour initialiser correctement
        updateEntityTypeFields();
    }

    // Ajouter un nouveau membre
    const addMemberBtn = document.getElementById('addMemberBtn');
    const membersContainer = document.getElementById('membersContainer');
    const memberTemplate = document.getElementById('memberTemplate');
    
    if (addMemberBtn && membersContainer && memberTemplate) {
        addMemberBtn.addEventListener('click', function() {
            // Supprimer l'alerte si elle existe
            const alert = membersContainer.querySelector('.alert-info');
            if (alert) {
                alert.remove();
            }
            
            // Cloner le template
            const newMember = memberTemplate.cloneNode(true);
            newMember.style.display = 'block';
            newMember.removeAttribute('id');
            
            // Remplacer tous les __INDEX__ par l'index actuel
            const htmlContent = newMember.innerHTML.replace(/__INDEX__/g, memberIndex);
            newMember.innerHTML = htmlContent;
            
            // Mettre à jour le numéro du membre
            const memberIndexSpan = newMember.querySelector('.member-index');
            if (memberIndexSpan) {
                memberIndexSpan.textContent = memberIndex + 1;
            }
            
            membersContainer.appendChild(newMember);
            memberIndex++;
        });
    }

    // Supprimer un membre
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-member-btn') || e.target.closest('.remove-member-btn')) {
            const btn = e.target.classList.contains('remove-member-btn') ? e.target : e.target.closest('.remove-member-btn');
            const memberItem = btn.closest('.member-item');
            if (memberItem) {
                if (confirm('Êtes-vous sûr de vouloir supprimer ce membre ?')) {
                    memberItem.remove();
                    
                    // Afficher l'alerte si plus aucun membre
                    if (membersContainer.querySelectorAll('.member-item').length === 0) {
                        membersContainer.innerHTML = '<div class="alert alert-info"><i class="bi bi-info-circle"></i> Aucun membre ajouté. Cliquez sur "Ajouter un membre" pour commencer.</div>';
                    }
                }
            }
        }
    });
});
</script>
@endpush
