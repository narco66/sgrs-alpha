@extends('layouts.app')

@section('title', 'Ajouter un membre - ' . $delegation->title)

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            {{-- En-tête --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="bi bi-person-plus text-primary"></i>
                        Ajouter un membre
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('delegations.index') }}">Délégations</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('delegations.show', $delegation) }}">{{ $delegation->title }}</a></li>
                            <li class="breadcrumb-item active">Nouveau membre</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('delegations.show', $delegation) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>

            {{-- Carte info délégation --}}
            <div class="card border-primary mb-4">
                <div class="card-body py-2">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-people-fill text-primary me-2"></i>
                        <span class="fw-semibold">{{ $delegation->title }}</span>
                        @if($delegation->country)
                            <span class="badge bg-secondary ms-2">{{ $delegation->country }}</span>
                        @endif
                        <span class="text-muted ms-auto">
                            {{ $delegation->members->count() }} membre(s) actuellement
                        </span>
                    </div>
                </div>
            </div>

            {{-- Formulaire --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-person"></i> Informations du membre
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('delegations.members.store', $delegation) }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            {{-- Prénom --}}
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">Prénom <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="first_name" 
                                       id="first_name"
                                       class="form-control @error('first_name') is-invalid @enderror"
                                       value="{{ old('first_name') }}"
                                       required
                                       autofocus>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Nom --}}
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="last_name" 
                                       id="last_name"
                                       class="form-control @error('last_name') is-invalid @enderror"
                                       value="{{ old('last_name') }}"
                                       required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" 
                                       name="email" 
                                       id="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}"
                                       placeholder="exemple@email.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Téléphone --}}
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Téléphone</label>
                                <input type="text" 
                                       name="phone" 
                                       id="phone"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone') }}"
                                       placeholder="+242 06 123 456 78">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Fonction / Position --}}
                            <div class="col-md-6">
                                <label for="position" class="form-label">Fonction / Position</label>
                                <input type="text" 
                                       name="position" 
                                       id="position"
                                       class="form-control @error('position') is-invalid @enderror"
                                       value="{{ old('position') }}"
                                       placeholder="Ex: Ministre, Ambassadeur, Conseiller">
                                @error('position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Titre / Grade --}}
                            <div class="col-md-6">
                                <label for="title" class="form-label">Titre / Grade</label>
                                <input type="text" 
                                       name="title" 
                                       id="title"
                                       class="form-control @error('title') is-invalid @enderror"
                                       value="{{ old('title') }}"
                                       placeholder="Ex: Son Excellence, Dr., Prof.">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Institution --}}
                            <div class="col-md-6">
                                <label for="institution" class="form-label">Institution</label>
                                <input type="text" 
                                       name="institution" 
                                       id="institution"
                                       class="form-control @error('institution') is-invalid @enderror"
                                       value="{{ old('institution') }}"
                                       placeholder="Ex: Ministère des Affaires Étrangères">
                                @error('institution')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Département --}}
                            <div class="col-md-6">
                                <label for="department" class="form-label">Département / Service</label>
                                <input type="text" 
                                       name="department" 
                                       id="department"
                                       class="form-control @error('department') is-invalid @enderror"
                                       value="{{ old('department') }}">
                                @error('department')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Rôle --}}
                            <div class="col-md-6">
                                <label for="role" class="form-label">Rôle dans la délégation <span class="text-danger">*</span></label>
                                <select name="role" 
                                        id="role"
                                        class="form-select @error('role') is-invalid @enderror"
                                        required>
                                    <option value="">Sélectionner un rôle</option>
                                    <option value="head" @selected(old('role') == 'head')>Chef de délégation</option>
                                    <option value="member" @selected(old('role', 'member') == 'member')>Membre</option>
                                    <option value="expert" @selected(old('role') == 'expert')>Expert</option>
                                    <option value="observer" @selected(old('role') == 'observer')>Observateur</option>
                                    <option value="secretary" @selected(old('role') == 'secretary')>Secrétaire</option>
                                    <option value="advisor" @selected(old('role') == 'advisor')>Conseiller</option>
                                    <option value="interpreter" @selected(old('role') == 'interpreter')>Interprète</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Statut --}}
                            <div class="col-md-6">
                                <label for="status" class="form-label">Statut</label>
                                <select name="status" 
                                        id="status"
                                        class="form-select @error('status') is-invalid @enderror">
                                    <option value="invited" @selected(old('status', 'invited') == 'invited')>Invité</option>
                                    <option value="confirmed" @selected(old('status') == 'confirmed')>Confirmé</option>
                                    <option value="present" @selected(old('status') == 'present')>Présent</option>
                                    <option value="absent" @selected(old('status') == 'absent')>Absent</option>
                                    <option value="excused" @selected(old('status') == 'excused')>Excusé</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Notes --}}
                            <div class="col-12">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea name="notes" 
                                          id="notes"
                                          rows="3" 
                                          class="form-control @error('notes') is-invalid @enderror"
                                          placeholder="Notes additionnelles sur ce membre...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Boutons --}}
                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('delegations.show', $delegation) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Ajouter le membre
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection










