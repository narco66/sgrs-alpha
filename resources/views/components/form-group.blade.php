@props(['label', 'name', 'type' => 'text', 'required' => false, 'help' => null, 'placeholder' => null])

<div class="mb-4">
    <label for="{{ $name }}" class="form-label">
        @if($required)
            <span class="text-danger">*</span>
        @endif
        {{ $label }}
    </label>
    
    @if($type === 'textarea')
        <textarea 
            class="form-control @error($name) is-invalid @enderror" 
            id="{{ $name }}" 
            name="{{ $name }}" 
            rows="4"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
        >{{ old($name, $slot ?? '') }}</textarea>
    @elseif($type === 'select')
        <select 
            class="form-select @error($name) is-invalid @enderror" 
            id="{{ $name }}" 
            name="{{ $name }}"
            {{ $required ? 'required' : '' }}
        >
            {{ $slot }}
        </select>
    @elseif($type === 'checkbox' || $type === 'radio')
        <div class="form-check">
            <input 
                class="form-check-input @error($name) is-invalid @enderror" 
                type="{{ $type }}" 
                id="{{ $name }}" 
                name="{{ $name }}"
                value="{{ $value ?? '1' }}"
                {{ old($name) ? 'checked' : '' }}
                {{ $required ? 'required' : '' }}
            >
            <label class="form-check-label" for="{{ $name }}">
                {{ $slot }}
            </label>
        </div>
    @else
        <input 
            type="{{ $type }}" 
            class="form-control @error($name) is-invalid @enderror" 
            id="{{ $name }}" 
            name="{{ $name }}" 
            value="{{ old($name, $value ?? '') }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes }}
        >
    @endif

    @error($name)
        <div class="invalid-feedback">
            <i class="bi bi-exclamation-circle me-1"></i>
            {{ $message }}
        </div>
    @enderror

    @if($help)
        <div class="form-text">
            <i class="bi bi-info-circle me-1"></i>
            {{ $help }}
        </div>
    @endif
</div>

