@csrf

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Titre de la réunion</label>
        <input type="text" name="title" class="form-control"
               value="{{ old('title', $meeting->title ?? '') }}" required>
    </div>

    <div class="col-md-3">
        <label class="form-label">Type de réunion</label>
        <select name="meeting_type_id" class="form-select">
            <option value="">(Non défini)</option>
            @foreach($meetingTypes as $type)
                <option value="{{ $type->id }}"
                    @selected(old('meeting_type_id', $meeting->meeting_type_id ?? null) == $type->id)>
                    {{ $type->name }} ({{ $type->code }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
        <label class="form-label">Comité</label>
        <select name="committee_id" class="form-select">
            <option value="">(Aucun)</option>
            @foreach($committees as $committee)
                <option value="{{ $committee->id }}"
                    @selected(old('committee_id', $meeting->committee_id ?? null) == $committee->id)>
                    {{ $committee->name }} ({{ $committee->code }})
                </option>
            @endforeach
        </select>
    </div>
</div>

{{-- le reste du formulaire : dates, salle, statut, rappel, etc. --}}

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $meeting->description ?? '') }}</textarea>
    </div>
</div>
