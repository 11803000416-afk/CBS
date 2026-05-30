<!-- Component: Responsive Button with WCAG Compliance -->
@php
    $baseClass = 'btn';
    $variantClass = isset($variant) ? "btn-{$variant}" : 'btn-primary';
    $sizeClass = isset($size) ? "btn-{$size}" : '';
    $disabled = isset($disabled) && $disabled ? 'disabled' : '';
    $classes = trim("$baseClass $variantClass $sizeClass $disabled");
    
    // Accessibility
    $ariaLabel = $attributes['aria-label'] ?? $text ?? '';
    $ariaDescribedBy = $attributes['aria-describedby'] ?? '';
@endphp

@if(isset($href))
    <a href="{{ $href }}" 
       class="{{ $classes }}"
       @if($ariaLabel) aria-label="{{ $ariaLabel }}" @endif
       @if($ariaDescribedBy) aria-describedby="{{ $ariaDescribedBy }}" @endif
       role="button"
       tabindex="0"
       {{ $attributes }}>
        @if(isset($icon))
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                {{ $icon }}
            </svg>
        @endif
        <span>{{ $text ?? 'Button' }}</span>
    </a>
@else
    <button type="{{ $type ?? 'button' }}"
            class="{{ $classes }}"
            @if($disabled) disabled @endif
            @if($ariaLabel) aria-label="{{ $ariaLabel }}" @endif
            @if($ariaDescribedBy) aria-describedby="{{ $ariaDescribedBy }}" @endif
            {{ $attributes }}>
        @if(isset($icon))
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                {{ $icon }}
            </svg>
        @endif
        <span>{{ $text ?? 'Button' }}</span>
    </button>
@endif
