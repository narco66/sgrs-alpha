@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center">
    <div class="card shadow-lg border-0" style="max-width: 800px; width: 100%;">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Ajouter un document</h5>
            <a href="{{ route('documents.index') }}" class="btn btn-sm btn-outline-secondary">
                Retour
            </a>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Titre du document</label>
                    <input type="text" name="title" class="form-control"
                           value="{{ old('title') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Description <span class="text-muted small">(optionnelle)</span>
                    </label>
                    <textarea name="description"
                              class="form-control"
                              rows="3">{{ old('description') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Fichier</label>
                    <input type="file" name="file" class="form-control" required>
                    <div class="form-text">
                        Formats recommandés : PDF, Word, Excel, PowerPoint. Taille maximale : 25 Mo.
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Type de document</label>
                        <select name="document_type" class="form-select" required>
                            <option value="ordre_du_jour" @selected(old('document_type') === 'ordre_du_jour')>Ordre du jour</option>
                            <option value="rapport" @selected(old('document_type') === 'rapport')>Rapport</option>
                            <option value="pv" @selected(old('document_type') === 'pv')>Procès-verbal</option>
                            <option value="presentation" @selected(old('document_type') === 'presentation')>Présentation</option>
                            <option value="note" @selected(old('document_type') === 'note')>Note</option>
                            <option value="autre" @selected(old('document_type') === 'autre' || !old('document_type'))>Autre</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Réunion associée</label>
                        <select name="meeting_id" class="form-select">
                            <option value="">(Aucune)</option>
                            @foreach($meetings as $meeting)
                                <option value="{{ $meeting->id }}" @selected(old('meeting_id') == $meeting->id)>
                                    {{ $meeting->title }} – {{ $meeting->start_at?->format('d/m/Y') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Partage</label>
                    <select name="is_shared" class="form-select">
                        <option value="1" @selected(old('is_shared', 1) == 1)>Document partagé avec les utilisateurs autorisés</option>
                        <option value="0" @selected(old('is_shared') === '0')>Document restreint</option>
                    </select>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary">
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Enregistrer le document
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
