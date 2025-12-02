@csrf

<div class="mb-3">
    <label class="form-label">Nom du comité</label>
    <input type="text" name="name" class="form-control"
           value="{{ old('name', $committee->name ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Code</label>
    <input type="text" name="code" class="form-control"
           value="{{ old('code', $committee->code ?? '') }}" required>
    <div class="form-text">Exemple : CE, CS, CT...</div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Type de réunion associé</label>
        <select name="meeting_type_id" class="form-select">
            <option value="">(Aucun)</option>
            @foreach($meetingTypes as $type)
                <option value="{{ $type->id }}"
                    @selected(old('meeting_type_id', $committee->meeting_type_id ?? null) == $type->id)>
                    {{ $type->name }} ({{ $type->code }})
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Nature</label>
        <select name="is_permanent" class="form-select">
            <option value="1" @selected(old('is_permanent', $committee->is_permanent ?? true))>Permanent</option>
            <option value="0" @selected(old('is_permanent', $committee->is_permanent ?? true) == false)>Ad hoc</option>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Statut</label>
        <select name="is_active" class="form-select">
            <option value="1" @selected(old('is_active', $committee->is_active ?? true))>Actif</option>
            <option value="0" @selected(old('is_active', $committee->is_active ?? true) == false)>Inactif</option>
        </select>
    </div>
</div>

<div class="mt-3 mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" rows="3" class="form-control">{{ old('description', $committee->description ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label class="form-label">Ordre d'affichage</label>
    <input type="number" name="sort_order" class="form-control"
           value="{{ old('sort_order', $committee->sort_order ?? 0) }}">
</div>
