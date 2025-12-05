@php
    $memberId = $member['id'] ?? null;
    $isHead = ($member['role'] ?? '') === 'head';
@endphp

<div class="member-item border rounded p-3 mb-3 {{ $isExisting ? 'bg-light' : '' }}">
    @if($isExisting)
        <input type="hidden" name="members[{{ $index }}][id]" value="{{ $memberId }}">
    @endif
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0">
            <i class="bi bi-person"></i> 
            Membre {{ $index + 1 }}
            @if($isExisting)
                <span class="badge bg-secondary">Existant</span>
            @endif
        </h6>
        <button type="button" class="btn btn-sm btn-outline-danger remove-member-btn">
            <i class="bi bi-trash"></i> Supprimer
        </button>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Prénom <span class="text-danger">*</span></label>
            <input type="text" 
                   name="members[{{ $index }}][first_name]" 
                   class="form-control @error("members.{$index}.first_name") is-invalid @enderror"
                   value="{{ old("members.{$index}.first_name", $member['first_name'] ?? '') }}"
                   required>
            @error("members.{$index}.first_name")
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Nom <span class="text-danger">*</span></label>
            <input type="text" 
                   name="members[{{ $index }}][last_name]" 
                   class="form-control @error("members.{$index}.last_name") is-invalid @enderror"
                   value="{{ old("members.{$index}.last_name", $member['last_name'] ?? '') }}"
                   required>
            @error("members.{$index}.last_name")
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" 
                   name="members[{{ $index }}][email]" 
                   class="form-control @error("members.{$index}.email") is-invalid @enderror"
                   value="{{ old("members.{$index}.email", $member['email'] ?? '') }}"
                   required>
            @error("members.{$index}.email")
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Téléphone</label>
            <input type="text" 
                   name="members[{{ $index }}][phone]" 
                   class="form-control @error("members.{$index}.phone") is-invalid @enderror"
                   value="{{ old("members.{$index}.phone", $member['phone'] ?? '') }}"
                   placeholder="+242 06 123 456 78">
            @error("members.{$index}.phone")
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Fonction / Position</label>
            <input type="text" 
                   name="members[{{ $index }}][position]" 
                   class="form-control @error("members.{$index}.position") is-invalid @enderror"
                   value="{{ old("members.{$index}.position", $member['position'] ?? '') }}"
                   placeholder="Ex: Ministre, Ambassadeur, Conseiller">
            @error("members.{$index}.position")
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Titre / Grade</label>
            <input type="text" 
                   name="members[{{ $index }}][title]" 
                   class="form-control @error("members.{$index}.title") is-invalid @enderror"
                   value="{{ old("members.{$index}.title", $member['title'] ?? '') }}"
                   placeholder="Ex: Son Excellence, Dr., Prof.">
            @error("members.{$index}.title")
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Institution</label>
            <input type="text" 
                   name="members[{{ $index }}][institution]" 
                   class="form-control @error("members.{$index}.institution") is-invalid @enderror"
                   value="{{ old("members.{$index}.institution", $member['institution'] ?? '') }}"
                   placeholder="Ex: Ministère des Affaires Étrangères">
            @error("members.{$index}.institution")
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Département / Service</label>
            <input type="text" 
                   name="members[{{ $index }}][department]" 
                   class="form-control @error("members.{$index}.department") is-invalid @enderror"
                   value="{{ old("members.{$index}.department", $member['department'] ?? '') }}">
            @error("members.{$index}.department")
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Rôle dans la délégation <span class="text-danger">*</span></label>
            <select name="members[{{ $index }}][role]" 
                    class="form-select @error("members.{$index}.role") is-invalid @enderror"
                    required>
                <option value="">Sélectionner un rôle</option>
                <option value="head" @selected(old("members.{$index}.role", $member['role'] ?? '') == 'head')>
                    Chef de délégation
                </option>
                <option value="member" @selected(old("members.{$index}.role", $member['role'] ?? 'member') == 'member')>
                    Membre
                </option>
                <option value="expert" @selected(old("members.{$index}.role", $member['role'] ?? '') == 'expert')>
                    Expert
                </option>
                <option value="observer" @selected(old("members.{$index}.role", $member['role'] ?? '') == 'observer')>
                    Observateur
                </option>
                <option value="secretary" @selected(old("members.{$index}.role", $member['role'] ?? '') == 'secretary')>
                    Secrétaire
                </option>
            </select>
            @error("members.{$index}.role")
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Statut</label>
            <select name="members[{{ $index }}][status]" 
                    class="form-select @error("members.{$index}.status") is-invalid @enderror">
                <option value="invited" @selected(old("members.{$index}.status", $member['status'] ?? 'invited') == 'invited')>
                    Invité
                </option>
                <option value="confirmed" @selected(old("members.{$index}.status", $member['status'] ?? '') == 'confirmed')>
                    Confirmé
                </option>
                <option value="present" @selected(old("members.{$index}.status", $member['status'] ?? '') == 'present')>
                    Présent
                </option>
                <option value="absent" @selected(old("members.{$index}.status", $member['status'] ?? '') == 'absent')>
                    Absent
                </option>
                <option value="excused" @selected(old("members.{$index}.status", $member['status'] ?? '') == 'excused')>
                    Excusé
                </option>
            </select>
            @error("members.{$index}.status")
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12">
            <label class="form-label">Notes</label>
            <textarea name="members[{{ $index }}][notes]" 
                      rows="2" 
                      class="form-control @error("members.{$index}.notes") is-invalid @enderror"
                      placeholder="Notes additionnelles sur ce membre...">{{ old("members.{$index}.notes", $member['notes'] ?? '') }}</textarea>
            @error("members.{$index}.notes")
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>




