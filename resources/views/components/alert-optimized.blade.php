<!-- Component: Accessible Alert/Toast Notification -->
@php
    $id = $id ?? 'alert-' . uniqid();
    $type = $type ?? 'info'; // info, success, warning, danger
    $dismissible = $dismissible ?? true;
@endphp

<div 
    class="alert alert-{{ $type }}"
    id="{{ $id }}"
    role="alert"
    aria-live="polite"
    aria-atomic="true">
    
    <div class="flex items-start gap-3">
        <!-- Icon -->
        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            @if($type === 'success')
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            @elseif($type === 'warning')
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0-6a4 4 0 100 8 4 4 0 000-8z"/>
            @elseif($type === 'danger')
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            @else
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            @endif
        </svg>

        <!-- Message -->
        <div class="flex-1">
            @if($title ?? false)
                <h3 class="font-semibold mb-1">{{ $title }}</h3>
            @endif
            <p>{{ $message ?? $slot }}</p>
        </div>

        <!-- Close Button -->
        @if($dismissible)
            <button 
                class="alert-close flex-shrink-0"
                onclick="this.parentElement.parentElement.remove()"
                aria-label="Close alert {{ $type }}"
                type="button">
                <span aria-hidden="true">&times;</span>
            </button>
        @endif
    </div>
</div>

<style>
    .alert {
        padding: 1rem;
        border-radius: 0.5rem;
        border-left: 4px solid;
        display: flex;
        gap: 0.75rem;
        margin-bottom: 1rem;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-success {
        background-color: #f0fdf4;
        color: #166534;
        border-color: #22c55e;
    }

    .alert-danger {
        background-color: #fef2f2;
        color: #991b1b;
        border-color: #ef4444;
    }

    .alert-warning {
        background-color: #fffbeb;
        color: #92400e;
        border-color: #f59e0b;
    }

    .alert-info {
        background-color: #f0f9ff;
        color: #0c3d66;
        border-color: #0ea5e9;
    }

    .alert-close {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1.5rem;
        padding: 0;
        color: inherit;
        opacity: 0.7;
        transition: opacity 0.2s ease;
    }

    .alert-close:hover {
        opacity: 1;
    }

    .alert-close:focus {
        outline: 2px solid currentColor;
        outline-offset: 2px;
    }
</style>
