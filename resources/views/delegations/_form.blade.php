@csrf

<div class="mb-3">
    <label class="form-label">Titre de la delegation <span class="text-danger">*</span></label>
    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
           value="{{ old('title', $delegation->title ?? '') }}" required>
    @error('title')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Code</label>
        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
               value="{{ old('code', $delegation->code ?? '') }}">
        <div class="form-text">Code unique pour identifier la delegation</div>
        @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Pays</label>
        <input type="text" name="country" class="form-control @error('country') is-invalid @enderror"
               value="{{ old('country', $delegation->country ?? '') }}">
        @error('country')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $delegation->description ?? '') }}</textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Reunion associee <span class="text-danger">*</span></label>
    <select name="meeting_id" class="form-select @error('meeting_id') is-invalid @enderror" required>
        <option value="">Selectionner une reunion</option>
        @foreach($meetings ?? [] as $meeting)
            <option value="{{ $meeting->id }}" @selected(old('meeting_id', $delegation->meeting_id ?? '') == $meeting->id)>
                {{ $meeting->title }} - {{ $meeting->start_at?->format('d/m/Y') }}
            </option>
        @endforeach
    </select>
    @error('meeting_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Participants de la delegation</label>
    @php
        $selectedParticipants = collect(old('participants', $delegation->participants?->pluck('id')->all() ?? []));
    @endphp
    <select name="participants[]" class="form-select @error('participants') is-invalid @enderror" multiple>
        @foreach($users ?? [] as $user)
            <option value="{{ $user->id }}" @selected($selectedParticipants->contains($user->id))>
                {{ $user->name }} @if($user->delegation_id && $user->delegation_id !== ($delegation->id ?? null)) - {{ $user->delegation?->title }} @endif
            </option>
        @endforeach
    </select>
    <div class="form-text">Selectionnez les utilisateurs associes a cette delegation.</div>
    @error('participants')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Email de contact</label>
        <input type="email" name="contact_email" class="form-control @error('contact_email') is-invalid @enderror"
               value="{{ old('contact_email', $delegation->contact_email ?? '') }}">
        @error('contact_email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Telephone de contact</label>
        <input type="text" name="contact_phone" class="form-control @error('contact_phone') is-invalid @enderror"
               value="{{ old('contact_phone', $delegation->contact_phone ?? '') }}">
        @error('contact_phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Adresse</label>
    <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
           value="{{ old('address', $delegation->address ?? '') }}">
    @error('address')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Statut</label>
    <select name="is_active" class="form-select @error('is_active') is-invalid @enderror">
        <option value="1" @selected(old('is_active', $delegation->is_active ?? true))>Actif</option>
        <option value="0" @selected(old('is_active', $delegation->is_active ?? true) == false)>Inactif</option>
    </select>
    @error('is_active')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
