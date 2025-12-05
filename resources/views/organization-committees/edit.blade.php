@extends('layouts.app')

@section('title', 'Modifier le comitǸ d\'organisation')

@section('content')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
        <li class="breadcrumb-item"><a href="{{ route('organization-committees.index') }}">ComitǸs d'organisation</a></li>
        <li class="breadcrumb-item active">Modifier</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold">Modifier le comitǸ d'organisation</h3>
        <p class="text-muted mb-0 small">Accueil / ComitǸs d'organisation / Modifier</p>
    </div>
</div>

@include('partials.alerts')

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('organization-committees.update', $organizationCommittee) }}">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nom du comitǸ <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $organizationCommittee->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="meeting_id" class="form-label">RǸunion associǸe</label>
                    <select class="form-select @error('meeting_id') is-invalid @enderror" id="meeting_id" name="meeting_id">
                        <option value="">Aucune</option>
                        @foreach($meetings as $meeting)
                            <option value="{{ $meeting->id }}" @selected(old('meeting_id', $organizationCommittee->meeting_id) == $meeting->id)>{{ $meeting->title }}</option>
                        @endforeach
                    </select>
                    @error('meeting_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $organizationCommittee->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', $organizationCommittee->is_active))>
                        <label class="form-check-label" for="is_active">ComitǸ actif</label>
                    </div>
                </div>
            </div>

            <hr>

            <h5 class="mb-3">Membres du comitǸ</h5>
            <div id="members-container">
                @forelse($organizationCommittee->members as $index => $member)
                    <div class="member-row mb-3 p-3 border rounded">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="form-label">Utilisateur <span class="text-danger">*</span></label>
                                <select class="form-select member-user" name="members[{{ $index }}][user_id]" required>
                                    <option value="">SǸlectionner un utilisateur</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" @selected($member->user_id == $user->id)>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">R��le <span class="text-danger">*</span></label>
                                <input type="text" class="form-control member-role" name="members[{{ $index }}][role]" value="{{ $member->role }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" class="btn btn-outline-danger w-100 remove-member">
                                    <i class="bi bi-trash"></i> Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="member-row mb-3 p-3 border rounded">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="form-label">Utilisateur <span class="text-danger">*</span></label>
                                <select class="form-select member-user" name="members[0][user_id]" required>
                                    <option value="">SǸlectionner un utilisateur</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">R��le <span class="text-danger">*</span></label>
                                <input type="text" class="form-control member-role" name="members[0][role]" value="member" required placeholder="ex: prǸsident, secrǸtaire, membre">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" class="btn btn-outline-danger w-100 remove-member" style="display: none;">
                                    <i class="bi bi-trash"></i> Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <template id="member-template">
                <div class="member-row mb-3 p-3 border rounded">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label">Utilisateur <span class="text-danger">*</span></label>
                            <select class="form-select member-user" name="members[__INDEX__][user_id]" required>
                                <option value="">SǸlectionner un utilisateur</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">R��le <span class="text-danger">*</span></label>
                            <input type="text" class="form-control member-role" name="members[__INDEX__][role]" value="member" required placeholder="ex: prǸsident, secrǸtaire, membre">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-outline-danger w-100 remove-member">
                                <i class="bi bi-trash"></i> Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            </template>

            <button type="button" class="btn btn-outline-primary mb-3" id="add-member">
                <i class="bi bi-plus-circle me-1"></i> Ajouter un membre
            </button>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('organization-committees.show', $organizationCommittee) }}" class="btn btn-outline-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-1"></i> Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const membersContainer = document.getElementById('members-container');
    const memberTemplate = document.getElementById('member-template');
    let memberIndex = membersContainer.querySelectorAll('.member-row').length;

    // Fonction pour réindexer les membres
    function reindexMembers() {
        const rows = membersContainer.querySelectorAll('.member-row');
        rows.forEach((row, index) => {
            row.querySelectorAll('select, input').forEach(input => {
                if (input.name && input.name.includes('members[')) {
                    // Remplacer l'ancien index par le nouveau
                    input.name = input.name.replace(/members\[\d+\]/, `members[${index}]`);
                }
            });
        });
        memberIndex = membersContainer.querySelectorAll('.member-row').length;
    }

    document.getElementById('add-member').addEventListener('click', function() {
        if (!memberTemplate?.content?.firstElementChild) return;

        const newRow = memberTemplate.content.firstElementChild.cloneNode(true);

        newRow.querySelectorAll('select, input').forEach(input => {
            if (input.name) {
                input.name = input.name.replace('__INDEX__', memberIndex);
            }
            if (input.tagName === 'SELECT') {
                input.selectedIndex = 0;
            }
            if (input.type === 'text') {
                input.value = 'member';
            }
        });

        membersContainer.appendChild(newRow);
        memberIndex++;
        reindexMembers();
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-member')) {
            const row = e.target.closest('.member-row');
            if (membersContainer.children.length > 1) {
                row.remove();
                reindexMembers();
            }
        }
    });

    // Réindexer avant la soumission du formulaire
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            reindexMembers();
            
            // Supprimer les membres vides (sans user_id sélectionné) pour éviter les erreurs de validation
            const rows = Array.from(membersContainer.querySelectorAll('.member-row'));
            rows.forEach(row => {
                const userSelect = row.querySelector('select[name*="[user_id]"]');
                if (userSelect && !userSelect.value) {
                    // Si le membre n'a pas de user_id et qu'il y a d'autres membres, le supprimer
                    if (membersContainer.children.length > 1) {
                        row.remove();
                    } else {
                        // Si c'est le dernier membre, désactiver les champs au lieu de le supprimer
                        row.querySelectorAll('select, input').forEach(input => {
                            input.removeAttribute('required');
                            input.disabled = true;
                        });
                    }
                }
            });
            
            // Réindexer à nouveau après suppression des membres vides
            reindexMembers();
            
            // S'assurer que le champ is_active est correctement géré
            const isActiveCheckbox = document.getElementById('is_active');
            if (isActiveCheckbox && !isActiveCheckbox.checked) {
                // Ajouter un champ caché pour indiquer que la checkbox n'est pas cochée
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'is_active';
                hiddenInput.value = '0';
                form.appendChild(hiddenInput);
            }
        });
    }
</script>
@endpush
@endsection
