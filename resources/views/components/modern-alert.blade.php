@props(['type' => 'info', 'dismissible' => false, 'icon' => null])

@php
    $icons = [
        'success' => 'bi-check-circle-fill',
        'danger' => 'bi-exclamation-triangle-fill',
        'warning' => 'bi-exclamation-circle-fill',
        'info' => 'bi-info-circle-fill',
    ];
    $icon = $icon ?? ($icons[$type] ?? 'bi-info-circle-fill');
@endphp

<div class="alert-modern alert-modern-{{ $type }} {{ $dismissible ? 'alert-dismissible' : '' }}" role="alert">
    <i class="bi {{ $icon }}"></i>
    <div class="flex-grow-1">
        {{ $slot }}
    </div>
    @if($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    @endif
</div>

