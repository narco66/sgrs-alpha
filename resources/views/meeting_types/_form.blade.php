@csrf

<div class="mb-3">
    <label class="form-label">Nom du type de réunion</label>
    <input type="text" name="name" class="form-control"
           value="{{ old('name', $meetingType->name ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Code</label>
    <input type="text" name="code" class="form-control"
           value="{{ old('code', $meetingType->code ?? '') }}" required>
    <div class="form-text">Exemple : CCE, CDM, CCE...</div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Couleur (badge)</label>
        <input type="text" name="color" class="form-control"
               value="{{ old('color', $meetingType->color ?? 'primary') }}">
        <div class="form-text">Classe Bootstrap (primary, danger...) ou code #HEX.</div>
    </div>
    <div class="col-md-4">
        <label class="form-label">Ordre d'affichage</label>
        <input type="number" name="sort_order" class="form-control"
               value="{{ old('sort_order', $meetingType->sort_order ?? 0) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Statut</label>
        <select name="is_active" class="form-select">
            <option value="1" @selected(old('is_active', $meetingType->is_active ?? true))>Actif</option>
            <option value="0" @selected(old('is_active', $meetingType->is_active ?? true) == false)>Inactif</option>
        </select>
    </div>
</div>

<div class="row g-3 mt-2">
    <div class="col-md-6">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                   name="requires_president_approval"
                   id="requires_president_approval"
                   value="1"
                   @checked(old('requires_president_approval', $meetingType->requires_president_approval ?? false))>
            <label class="form-check-label" for="requires_president_approval">
                Approbation de la Présidence requise
            </label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                   name="requires_sg_approval"
                   id="requires_sg_approval"
                   value="1"
                   @checked(old('requires_sg_approval', $meetingType->requires_sg_approval ?? true))>
            <label class="form-check-label" for="requires_sg_approval">
                Approbation du Secrétariat Général requise
            </label>
        </div>
    </div>
</div>

<div class="mt-3 mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" rows="3" class="form-control">{{ old('description', $meetingType->description ?? '') }}</textarea>
</div>
