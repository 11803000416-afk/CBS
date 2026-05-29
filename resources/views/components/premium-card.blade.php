@props(['title' => null, 'action' => null, 'class' => ''])

<div class="premium-card {{ $class }}">
    @if($title || $action)
        <div class="premium-card-header flex items-center justify-between gap-4">
            @if($title)
                <div>
                    <h3 class="text-lg font-bold text-slate-900">{{ $title }}</h3>
                </div>
            @endif

            @if($action)
                <a href="{{ $action }}" class="inline-flex items-center gap-2 rounded-full bg-cyan-50 px-3 py-1.5 text-sm font-semibold text-cyan-700 transition hover:bg-cyan-100 hover:text-cyan-800">
                    View All
                    <span aria-hidden="true">→</span>
                </a>
            @endif
        </div>
    @endif

    <div class="premium-card-body">
        {{ $slot }}
    </div>
</div>