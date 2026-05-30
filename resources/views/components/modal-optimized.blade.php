<!-- Component: Accessible Modal/Dialog -->
@php
    $id = $id ?? 'modal-' . uniqid();
    $size = $size ?? 'md'; // sm, md, lg
    $sizeClass = match($size) {
        'sm' => 'max-w-sm',
        'lg' => 'max-w-2xl',
        default => 'max-w-md',
    };
@endphp

<div 
    class="modal-backdrop"
    id="{{ $id }}-backdrop"
    data-modal-close="#{{ $id }}"
    style="display: none;"
    aria-hidden="true">
    
    <div class="modal {{ $sizeClass }}"
         id="{{ $id }}"
         role="dialog"
         aria-modal="true"
         @if($title ?? false) aria-labelledby="{{ $id }}-title" @endif
         @if($description ?? false) aria-describedby="{{ $id }}-description" @endif>
        
        <!-- Header -->
        <div class="modal-header">
            @if($title ?? false)
                <h2 id="{{ $id }}-title" class="text-xl font-bold">
                    {{ $title }}
                </h2>
            @endif
            <button 
                type="button"
                class="text-gray-500 hover:text-gray-700 transition"
                aria-label="Close modal"
                data-modal-close="#{{ $id }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div class="modal-body"
             @if($description ?? false) id="{{ $id }}-description" @endif>
            {{ $slot }}
        </div>

        <!-- Footer -->
        @if(isset($actions) || isset($footer))
            <div class="modal-footer">
                {{ $footer ?? $actions ?? '' }}
            </div>
        @endif
    </div>
</div>

<script>
    // Modal trigger setup
    document.querySelectorAll('[data-modal-open="#{{ $id }}-backdrop"]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('{{ $id }}-backdrop').style.display = 'flex';
            document.getElementById('{{ $id }}').setAttribute('aria-hidden', 'false');
        });
    });
</script>
