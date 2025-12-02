@extends('layouts.app')

@section('title', 'Créer un Rôle')

@section('content')
<div class="container-fluid py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Rôles</a></li>
            <li class="breadcrumb-item active">Créer</li>
        </ol>
    </nav>

    {{-- En-tête --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title">
                <i class="bi bi-plus-circle text-primary"></i>
                Créer un Nouveau Rôle
            </h2>
        </div>
        <a href="{{ route('roles.index') }}" class="btn btn-modern btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    @include('partials.alerts')

    <div class="modern-card">
        <div class="modern-card-body">
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf

                {{-- Nom du rôle --}}
                <div class="form-group mb-4">
                    <label class="form-label">
                        <i class="bi bi-tag"></i>
                        Nom du rôle
                        <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name') }}" 
                           placeholder="Ex: organisateur, moderateur, etc."
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">
                        Le nom doit être unique et en minuscules (ex: organisateur-reunion)
                    </small>
                </div>

                {{-- Permissions --}}
                <div class="form-group mb-4">
                    <label class="form-label">
                        <i class="bi bi-key"></i>
                        Permissions
                    </label>
                    <div class="permissions-container">
                        @foreach($allPermissions as $module => $permissions)
                            <div class="permission-module mb-3">
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <div class="form-check">
                                            <input class="form-check-input module-checkbox" 
                                                   type="checkbox" 
                                                   id="module-{{ $module }}"
                                                   data-module="{{ $module }}">
                                            <label class="form-check-label fw-bold" for="module-{{ $module }}">
                                                {{ ucfirst(str_replace('_', ' ', $module)) }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-2">
                                            @foreach($permissions as $permission)
                                                <div class="col-md-6 col-lg-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input permission-checkbox" 
                                                               type="checkbox" 
                                                               name="permissions[]" 
                                                               value="{{ $permission->id }}"
                                                               id="permission-{{ $permission->id }}"
                                                               data-module="{{ $module }}">
                                                        <label class="form-check-label" for="permission-{{ $permission->id }}">
                                                            {{ $permission->name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Actions --}}
                <div class="d-flex justify-content-between">
                    <a href="{{ route('roles.index') }}" class="btn btn-modern btn-outline-secondary">
                        <i class="bi bi-x-circle"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-modern btn-primary">
                        <i class="bi bi-check-circle"></i> Créer le Rôle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Sélection/désélection par module
    document.querySelectorAll('.module-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const module = this.dataset.module;
            const modulePermissions = document.querySelectorAll(
                `.permission-checkbox[data-module="${module}"]`
            );
            modulePermissions.forEach(perm => {
                perm.checked = this.checked;
            });
        });
    });

    // Mise à jour de l'état du checkbox module
    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const module = this.dataset.module;
            const moduleCheckbox = document.querySelector(`#module-${module}`);
            const modulePermissions = document.querySelectorAll(
                `.permission-checkbox[data-module="${module}"]`
            );
            const checkedCount = Array.from(modulePermissions).filter(p => p.checked).length;
            
            if (checkedCount === 0) {
                moduleCheckbox.checked = false;
                moduleCheckbox.indeterminate = false;
            } else if (checkedCount === modulePermissions.length) {
                moduleCheckbox.checked = true;
                moduleCheckbox.indeterminate = false;
            } else {
                moduleCheckbox.checked = false;
                moduleCheckbox.indeterminate = true;
            }
        });
    });
</script>
@endpush
@endsection

