@extends('layouts.app')

@section('title', 'Créer un comité d\'organisation')

@section('content')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
        <li class="breadcrumb-item"><a href="{{ route('organization-committees.index') }}">Comités d'organisation</a></li>
        <li class="breadcrumb-item active">Créer</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold">Créer un comité d'organisation</h3>
        <p class="text-muted mb-0 small">Accueil / Comités d'organisation / Créer</p>
    </div>
</div>

@include('partials.alerts')

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('organization-committees.store') }}" id="committeeForm">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nom du comité <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="meeting_id" class="form-label">Réunion associée</label>
                    <select class="form-select @error('meeting_id') is-invalid @enderror" id="meeting_id" name="meeting_id">
                        <option value="">Aucune</option>
                        @if($meeting)
                            <option value="{{ $meeting->id }}" selected>{{ $meeting->title }}</option>
                        @endif
                    </select>
                    @error('meeting_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr>

            <h5 class="mb-3">Membres du comité</h5>
            <div id="members-container">
                <div class="member-row mb-3 p-3 border rounded">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label">Utilisateur <span class="text-danger">*</span></label>
                            <select class="form-select member-user" name="members[0][user_id]" required>
                                <option value="">Sélectionner un utilisateur</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Rôle <span class="text-danger">*</span></label>
                            <input type="text" class="form-control member-role" name="members[0][role]" value="member" required placeholder="ex: président, secrétaire, membre">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-outline-danger w-100 remove-member" style="display: none;">
                                <i class="bi bi-trash"></i> Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-outline-primary mb-3" id="add-member">
                <i class="bi bi-plus-circle me-1"></i> Ajouter un membre
            </button>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('organization-committees.index') }}" class="btn btn-outline-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-1"></i> Créer le comité
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let memberIndex = 1;
    
    document.getElementById('add-member').addEventListener('click', function() {
        const container = document.getElementById('members-container');
        const newRow = container.firstElementChild.cloneNode(true);
        
        // Mettre à jour les noms des champs
        newRow.querySelectorAll('select, input').forEach(input => {
            if (input.name) {
                input.name = input.name.replace(/\[0\]/, `[${memberIndex}]`);
            }
            if (input.value && input.tagName === 'SELECT') {
                input.value = '';
            }
            if (input.value && input.tagName === 'INPUT' && input.type === 'text') {
                input.value = '';
            }
        });
        
        // Afficher le bouton supprimer
        newRow.querySelector('.remove-member').style.display = 'block';
        
        container.appendChild(newRow);
        memberIndex++;
    });
    
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-member')) {
            const row = e.target.closest('.member-row');
            if (document.getElementById('members-container').children.length > 1) {
                row.remove();
            }
        }
    });
</script>
@endpush
@endsection

