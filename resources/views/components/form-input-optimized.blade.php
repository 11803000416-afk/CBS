<!-- Component: Accessible Form Input with Validation -->
@php
    $id = $id ?? $name ?? 'input-' . uniqid();
    $required = $required ?? false;
    $disabled = $disabled ?? false;
    $classes = "form-group";
    $error = $errors->first($name) ?? null;
@endphp

<div class="{{ $classes }}">
    @if(isset($label))
        <label for="{{ $id }}" class="form-label">
            {{ $label }}
            @if($required)
                <span class="required" title="This field is required" aria-label="required">*</span>
            @endif
        </label>
    @endif

    @if($type === 'textarea')
        <textarea 
            id="{{ $id }}"
            name="{{ $name }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($error) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
            class="form-input {{ $error ? 'is-invalid' : '' }}"
            rows="{{ $rows ?? 4 }}"
            {{ $attributes }}>{{ old($name, $value ?? '') }}</textarea>
    @elseif($type === 'select')
        <select 
            id="{{ $id }}"
            name="{{ $name }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($error) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
            class="form-input {{ $error ? 'is-invalid' : '' }}"
            {{ $attributes }}>
            <option value="">{{ $placeholder ?? 'Select an option' }}</option>
            @foreach($options ?? [] as $value => $label)
                <option value="{{ $value }}" @selected(old($name, $selected ?? false) == $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    @else
        <input 
            type="{{ $type ?? 'text' }}"
            id="{{ $id }}"
            name="{{ $name }}"
            value="{{ old($name, $value ?? '') }}"
            placeholder="{{ $placeholder ?? '' }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($error) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
            class="form-input {{ $error ? 'is-invalid' : '' }}"
            {{ $attributes }}>
    @endif

    @if($hint ?? false)
        <p class="help-text" id="{{ $id }}-hint">{{ $hint }}</p>
    @endif

    @if($error)
        <p class="error-text" id="{{ $id }}-error" role="alert">
            {{ $error }}
        </p>
    @endif
</div>
