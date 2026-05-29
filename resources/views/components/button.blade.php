<!-- Button Component -->
@php
    $baseClasses = 'inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2';
    
    $variants = [
        'primary' => 'bg-blue-600 hover:bg-blue-700 text-white focus:ring-blue-500',
        'secondary' => 'bg-gray-200 hover:bg-gray-300 text-gray-900 focus:ring-gray-400',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500',
        'success' => 'bg-emerald-600 hover:bg-emerald-700 text-white focus:ring-emerald-500',
        'outline' => 'border-2 border-blue-600 text-blue-600 hover:bg-blue-50 focus:ring-blue-500',
    ];
    
    $sizes = [
        'sm' => 'text-sm px-3 py-1.5',
        'md' => 'text-base px-4 py-2',
        'lg' => 'text-lg px-6 py-3',
    ];
    
    $variant = $variant ?? 'primary';
    $size = $size ?? 'md';
    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

<button {{ $attributes->merge(['class' => $classes, 'type' => 'button']) }}>
    {{ $slot }}
</button>
