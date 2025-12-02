@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center">
    <div class="card shadow-lg border-0" style="max-width: 900px; width: 100%;">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Modifier la réunion</h5>
            <a href="{{ route('meetings.index') }}" class="btn btn-sm btn-outline-secondary">
                Fermer
            </a>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('meetings.update', $meeting) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    {{-- Titre --}}
                    <div class="col-md-6">
                        <label class="form-label">Titre</label>
                        <input type="text"
                               name="title"
                               class="form-control"
                               placeholder="Tapez le titre ici"
                               value="{{ old('title', $meeting->title) }}"
                               required>
                    </div>

                    {{-- Type de réunion --}}
                    <div class="col-md-6">
                        <label class="form-label">Type de réunion</label>
                        <select name="meeting_type_id" class="form-select" required>
                            <option value="">Sélectionner</option>
                            @foreach($meetingTypes as $type)
                                <option value="{{ $type->id }}" @selected(old('meeting_type_id', $meeting->meeting_type_id) == $type->id)>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Date --}}
                    <div class="col-md-4">
                        <label class="form-label">Date</label>
                        <input type="date"
                               name="date"
                               class="form-control"
                               value="{{ old('date', optional($meeting->start_at ?? $meeting->date ?? null)->format('Y-m-d')) }}"
                               required>
                    </div>

                    {{-- Heure --}}
                    <div class="col-md-4">
                        <label class="form-label">Heure</label>
                        <input type="time"
                               name="time"
                               class="form-control"
                               value="{{ old('time', optional($meeting->start_at ?? $meeting->time ?? null)->format('H:i')) }}"
                               required>
                    </div>

                    {{-- Durée --}}
                    <div class="col-md-4">
                        <label class="form-label">Durée</label>
                        <select name="duration_minutes" class="form-select" required>
                            @foreach([30, 60, 90, 120, 180, 240] as $minutes)
                                <option value="{{ $minutes }}" @selected(old('duration_minutes', $meeting->duration_minutes) == $minutes)>
                                    {{ $minutes }} minutes
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Description --}}
                    <div class="col-12">
                        <label class="form-label">
                            Description <span class="text-muted small">(optionnelle)</span>
                        </label>
                        <textarea name="description"
                                  rows="4"
                                  class="form-control"
                                  placeholder="Entrez une description qui accompagnera l'invitation envoyée à chaque participant (max. 1000 mots)">{{ old('description', $meeting->description) }}</textarea>
                    </div>

                    {{-- Configuration --}}
                    <div class="col-md-6">
                        <label class="form-label">Configuration</label>
                        <select name="configuration" class="form-select" required>
                            <option value="presentiel" @selected(old('configuration', $meeting->configuration) === 'presentiel')>Présentiel</option>
                            <option value="hybride" @selected(old('configuration', $meeting->configuration) === 'hybride')>Hybride</option>
                            <option value="visioconference" @selected(old('configuration', $meeting->configuration) === 'visioconference')>Visioconférence</option>
                        </select>
                    </div>

                    {{-- Salle --}}
                    <div class="col-md-6">
                        <label class="form-label">Salle de réunion</label>
                        <select name="room_id" class="form-select">
                            <option value="">Sélectionner une salle</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" @selected(old('room_id', $meeting->room_id) == $room->id)>
                                    {{ $room->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Rappel --}}
                    <div class="col-md-6">
                        <label class="form-label">Rappel</label>
                        <select name="reminder_minutes_before" class="form-select">
                            @php
                                $values = [0 => 'Aucun', 5 => '5 minutes', 10 => '10 minutes', 15 => '15 minutes', 30 => '30 minutes', 60 => '1 heure', 120 => '2 heures', 1440 => '1 jour'];
                            @endphp
                            @foreach($values as $val => $label)
                                <option value="{{ $val }}" @selected(old('reminder_minutes_before', $meeting->reminder_minutes_before) == $val)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Comité d'Organisation (EF20) --}}
                    <div class="col-12">
                        <label class="form-label">
                            <i class="bi bi-people-fill"></i>
                            Comité d'Organisation (optionnel)
                        </label>
                        <select name="organization_committee_id" class="form-select">
                            <option value="">Aucun comité assigné</option>
                            @php
                                $committees = \App\Models\OrganizationCommittee::where('is_active', true)
                                    ->where(function($q) use ($meeting) {
                                        $q->whereNull('meeting_id')
                                          ->orWhere('meeting_id', $meeting->id);
                                    })
                                    ->orderBy('name')
                                    ->get();
                                $currentCommitteeId = $meeting->organizationCommittee?->id;
                            @endphp
                            @foreach($committees as $committee)
                                <option value="{{ $committee->id }}" @selected(old('organization_committee_id', $currentCommitteeId) == $committee->id)>
                                    {{ $committee->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">
                            Assignez un comité d'organisation à cette réunion. 
                            <a href="{{ route('organization-committees.create', ['meeting_id' => $meeting->id]) }}" target="_blank" class="text-decoration-none">
                                Créer un nouveau comité
                            </a>
                        </div>
                    </div>

                    {{-- Participants --}}
                    <div class="col-12">
                        <label class="form-label">Participants</label>
                        <select name="participants[]" class="form-select" multiple>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}"
                                    @selected(in_array($user->id, old('participants', $meeting->participantsUsers->pluck('id')->toArray())))>
                                    {{ $user->name }} — {{ $user->email }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">
                            Sélectionnez les participants invités à cette réunion.
                        </div>
                    </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('meetings.index') }}" class="btn btn-outline-secondary">
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Modifier la réunion
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
