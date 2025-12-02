@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Modifier la réunion</h4>
            <p class="text-muted mb-0">
                Mise à jour des informations de la réunion statutaire.
            </p>
        </div>
        <a href="{{ route('meetings.show', $meeting) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Retour aux détails
        </a>
    </div>

    {{-- Affichage des messages de succès / erreur --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Merci de corriger les erreurs suivantes :</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li class="small">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        use App\Enums\MeetingStatus;

        // Statuts disponibles à partir de l'enum
        $statusCases = MeetingStatus::cases();

        // Fonction utilitaire pour savoir si un statut est sélectionné
        $currentStatus = $meeting->status instanceof MeetingStatus
            ? $meeting->status->value
            : ($meeting->status ?? 'brouillon');
    @endphp

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('meetings.update', $meeting) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    {{-- Titre --}}
                    <div class="col-md-8">
                        <label for="title" class="form-label">Titre de la réunion <span class="text-danger">*</span></label>
                        <input type="text"
                               name="title"
                               id="title"
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $meeting->title) }}"
                               required>
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Type de réunion --}}
                    <div class="col-md-4">
                        <label for="meeting_type_id" class="form-label">Type de réunion</label>
                        <select name="meeting_type_id"
                                id="meeting_type_id"
                                class="form-select @error('meeting_type_id') is-invalid @enderror">
                            <option value="">— Sélectionner —</option>
                            @foreach($meetingTypes as $type)
                                <option value="{{ $type->id }}"
                                    @selected(old('meeting_type_id', $meeting->meeting_type_id) == $type->id)>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('meeting_type_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Comité --}}
                    <div class="col-md-6">
                        <label for="committee_id" class="form-label">Comité / Organe</label>
                        <select name="committee_id"
                                id="committee_id"
                                class="form-select @error('committee_id') is-invalid @enderror">
                            <option value="">— Sélectionner —</option>
                            @foreach($committees as $committee)
                                <option value="{{ $committee->id }}"
                                    @selected(old('committee_id', $meeting->committee_id) == $committee->id)>
                                    {{ $committee->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('committee_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Salle --}}
                    <div class="col-md-6">
                        <label for="room_id" class="form-label">Salle</label>
                        <select name="room_id"
                                id="room_id"
                                class="form-select @error('room_id') is-invalid @enderror">
                            <option value="">— Non attribuée —</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}"
                                    @selected(old('room_id', $meeting->room_id) == $room->id)>
                                    {{ $room->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('room_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Date et heure de début --}}
                    <div class="col-md-4">
                        <label for="start_at" class="form-label">Date et heure de début <span class="text-danger">*</span></label>
                        <input type="datetime-local"
                               name="start_at"
                               id="start_at"
                               class="form-control @error('start_at') is-invalid @enderror"
                               value="{{ old('start_at', optional($meeting->start_at)->format('Y-m-d\TH:i')) }}"
                               required>
                        @error('start_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Date et heure de fin --}}
                    <div class="col-md-4">
                        <label for="end_at" class="form-label">Date et heure de fin</label>
                        <input type="datetime-local"
                               name="end_at"
                               id="end_at"
                               class="form-control @error('end_at') is-invalid @enderror"
                               value="{{ old('end_at', optional($meeting->end_at)->format('Y-m-d\TH:i')) }}">
                        @error('end_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Durée en minutes --}}
                    <div class="col-md-4">
                        <label for="duration_minutes" class="form-label">Durée (minutes)</label>
                        <input type="number"
                               name="duration_minutes"
                               id="duration_minutes"
                               class="form-control @error('duration_minutes') is-invalid @enderror"
                               value="{{ old('duration_minutes', $meeting->duration_minutes) }}"
                               min="0" step="15">
                        @error('duration_minutes')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Statut --}}
                    <div class="col-md-4">
                        <label for="status" class="form-label">Statut de la réunion</label>
                        <select name="status"
                                id="status"
                                class="form-select @error('status') is-invalid @enderror">
                            @foreach($statusCases as $statusCase)
                                @php
                                    $value = $statusCase->value;
                                @endphp
                                <option value="{{ $value }}"
                                    @selected(old('status', $currentStatus) === $value)>
                                    {{ $statusCase->label() }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Rappel --}}
                    <div class="col-md-4">
                        <label for="reminder_minutes_before" class="form-label">Rappel avant la réunion</label>
                        <select name="reminder_minutes_before"
                                id="reminder_minutes_before"
                                class="form-select @error('reminder_minutes_before') is-invalid @enderror">
                            @php
                                $reminder = old('reminder_minutes_before', $meeting->reminder_minutes_before);
                            @endphp
                            <option value="0"  @selected($reminder == 0)>Aucun rappel</option>
                            <option value="15" @selected($reminder == 15)>15 minutes avant</option>
                            <option value="30" @selected($reminder == 30)>30 minutes avant</option>
                            <option value="60" @selected($reminder == 60)>1 heure avant</option>
                            <option value="120" @selected($reminder == 120)>2 heures avant</option>
                            <option value="1440" @selected($reminder == 1440)>1 jour avant</option>
                        @error('reminder_minutes_before')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Participants (multi-sélection) --}}
                    <div class="col-md-8">
                        <label for="participants" class="form-label">Participants</label>
                        @php
                            $selectedParticipants = old('participants', $meeting->participantsUsers->pluck('id')->toArray());
                        @endphp
                        <select name="participants[]"
                                id="participants"
                                class="form-select @error('participants') is-invalid @enderror"
                                multiple>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}"
                                    @selected(in_array($user->id, $selectedParticipants, true))>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">
                            Maintenir <kbd>Ctrl</kbd> (ou <kbd>Cmd</kbd> sur Mac) pour sélectionner plusieurs participants.
                        </div>
                        @error('participants')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="col-md-6">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description"
                                  id="description"
                                  rows="3"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Objectif, contexte, éléments importants...">{{ old('description', $meeting->description) }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Ordre du jour --}}
                    <div class="col-md-6">
                        <label for="agenda" class="form-label">Ordre du jour</label>
                        <textarea name="agenda"
                                  id="agenda"
                                  rows="3"
                                  class="form-control @error('agenda') is-invalid @enderror"
                                  placeholder="Liste des points à l'ordre du jour...">{{ old('agenda', $meeting->agenda) }}</textarea>
                        @error('agenda')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('meetings.show', $meeting) }}" class="btn btn-light">
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
