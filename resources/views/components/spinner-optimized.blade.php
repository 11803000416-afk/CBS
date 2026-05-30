<!-- Component: Loading Spinner -->
@php
    $id = $id ?? 'spinner-' . uniqid();
    $size = $size ?? 'md'; // sm, md, lg
    $sizeClass = match($size) {
        'sm' => 'w-4 h-4 border-2',
        'lg' => 'w-8 h-8 border-4',
        default => 'w-6 h-6 border-3',
    };
    $color = $color ?? 'border-blue-500';
@endphp

<div 
    class="flex items-center justify-center"
    @if($message) aria-label="{{ $message }}" @endif
    role="status">
    <div class="spinner {{ $sizeClass }} {{ $color }}"
         aria-hidden="true"></div>
    @if($message)
        <span class="sr-only">{{ $message }}</span>
    @endif
</div>

<style>
    .spinner {
        border: 3px solid rgba(2, 132, 199, 0.1);
        border-top-color: #0284c7;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    @media (prefers-reduced-motion: reduce) {
        .spinner {
            animation: none;
            border-top-color: transparent;
            border-right-color: #0284c7;
        }
    }
</style>
