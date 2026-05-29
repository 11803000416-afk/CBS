<!-- Alert Component -->
@php
    $types = [
        'success' => [
            'bg' => 'bg-emerald-50',
            'border' => 'border-emerald-200',
            'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
            'text' => 'text-emerald-900',
            'heading' => 'text-emerald-900',
            'icon-color' => 'text-emerald-600',
        ],
        'error' => [
            'bg' => 'bg-red-50',
            'border' => 'border-red-200',
            'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            'text' => 'text-red-900',
            'heading' => 'text-red-900',
            'icon-color' => 'text-red-600',
        ],
        'warning' => [
            'bg' => 'bg-amber-50',
            'border' => 'border-amber-200',
            'icon' => 'M12 9v2m0 4v2m0 6H7a2 2 0 01-2-2V5a2 2 0 012-2h10a2 2 0 012 2v16a2 2 0 01-2 2z',
            'text' => 'text-amber-900',
            'heading' => 'text-amber-900',
            'icon-color' => 'text-amber-600',
        ],
        'info' => [
            'bg' => 'bg-blue-50',
            'border' => 'border-blue-200',
            'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            'text' => 'text-blue-900',
            'heading' => 'text-blue-900',
            'icon-color' => 'text-blue-600',
        ],
    ];
    
    $type = $type ?? 'info';
    $style = $types[$type] ?? $types['info'];
@endphp

<div class="{{ $style['bg'] }} {{ $style['border'] }} border rounded-lg p-4 flex items-start gap-4 shadow-sm">
    <svg class="w-5 h-5 {{ $style['icon-color'] }} mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $style['icon'] }}"/>
    </svg>
    <div class="flex-1">
        @if($title ?? false)
            <p class="font-semibold {{ $style['heading'] }} mb-1">{{ $title }}</p>
        @endif
        <p class="{{ $style['text'] }} text-sm">{{ $slot }}</p>
    </div>
    @if($dismissible ?? false)
        <button class="text-gray-400 hover:text-gray-600" onclick="this.parentElement.remove()">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    @endif
</div>
