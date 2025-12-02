{{-- resources/views/partials/alerts.blade.php --}}

@php
    // Mapping simple des clés de session vers les types d'alertes modernes
    $flashTypes = [
        'success' => 'success',
        'error'   => 'danger',
        'warning' => 'warning',
        'info'    => 'info',
    ];
@endphp

<div class="mb-3">
    {{-- Messages flash simples (success, error, warning, info) --}}
    @foreach($flashTypes as $key => $type)
        @if(session($key))
            <x-modern-alert type="{{ $type }}" dismissible>
                {!! session($key) !!}
            </x-modern-alert>
        @endif
    @endforeach

    {{-- Gestion d'un éventuel message "status" (Laravel Breeze / Jetstream) --}}
    @if (session('status'))
        <x-modern-alert type="success" dismissible>
            {!! session('status') !!}
        </x-modern-alert>
    @endif

    {{-- Erreurs de validation --}}
    @if ($errors->any())
        <x-modern-alert type="danger" dismissible>
            <div class="fw-semibold mb-2">
                Une erreur est survenue. Merci de vérifier les informations saisies :
            </div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-modern-alert>
    @endif
</div>
