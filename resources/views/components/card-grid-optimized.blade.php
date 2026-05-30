<!-- Component: Card Container with Responsive Grid -->
@php
    $containerClass = isset($container) ? $container : 'card';
    $gridCols = $gridCols ?? 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4';
    $gap = $gap ?? 'gap-6';
@endphp

<div class="grid {{ $gridCols }} {{ $gap }}">
    @foreach($items as $item)
        <div class="{{ $containerClass }}">
            @if(isset($header))
                <div class="card-header">
                    {!! $header($item) !!}
                </div>
            @endif

            <div class="card-body">
                {!! $body($item) !!}
            </div>

            @if(isset($footer))
                <div class="card-footer">
                    {!! $footer($item) !!}
                </div>
            @endif
        </div>
    @endforeach
</div>

@if(empty($items))
    <div class="col-span-full">
        <div class="bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 p-12 text-center">
            <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <p class="text-gray-600 font-medium">{{ $empty ?? 'No items to display' }}</p>
        </div>
    </div>
@endif
