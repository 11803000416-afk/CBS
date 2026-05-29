<!-- Professional Button Component -->
<!-- Usage: @include('button', ['text' => 'Click Me', 'href' => '/url', 'type' => 'primary|secondary|danger|success']) -->
@php
    $base = 'px-6 py-3 rounded-lg font-semibold transition-all shadow-sm hover:shadow-md inline-flex items-center gap-2';
    $types = [
        'primary' => 'bg-gradient-to-r from-blue-600 to-blue-700 text-white hover:from-blue-700 hover:to-blue-800',
        'secondary' => 'bg-gray-200 text-gray-900 hover:bg-gray-300',
        'danger' => 'bg-gradient-to-r from-red-600 to-red-700 text-white hover:from-red-700 hover:to-red-800',
        'success' => 'bg-gradient-to-r from-green-600 to-green-700 text-white hover:from-green-700 hover:to-green-800',
        'outline' => 'border-2 border-blue-600 text-blue-600 hover:bg-blue-50',
    ];
    $type = $types[$type ?? 'primary'] ?? $types['primary'];
@endphp

@if($href ?? false)
    <a href="{{ $href }}" class="{{ $base }} {{ $type }}">
        {{ $text ?? 'Button' }}
        @if($icon ?? false)
            {!! $icon !!}
        @endif
    </a>
@else
    <button class="{{ $base }} {{ $type }}" {{ $attributes }}>
        {{ $text ?? 'Button' }}
        @if($icon ?? false)
            {!! $icon !!}
        @endif
    </button>
@endif
