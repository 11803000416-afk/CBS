<!-- Alert Component -->
<!-- Usage: @include('alert', ['type' => 'success|warning|error|info', 'message' => 'Your message here']) -->
<div class="p-4 rounded-xl border shadow-sm bg-{{ $type === 'success' ? 'green' : ($type === 'error' ? 'red' : ($type === 'warning' ? 'yellow' : 'blue')) }}-50 border-{{ $type === 'success' ? 'green' : ($type === 'error' ? 'red' : ($type === 'warning' ? 'yellow' : 'blue')) }}-200 text-{{ $type === 'success' ? 'green' : ($type === 'error' ? 'red' : ($type === 'warning' ? 'yellow' : 'blue')) }}-900 flex items-start gap-3">
    @if($type === 'success')
        <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    @elseif($type === 'error')
        <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    @elseif($type === 'warning')
        <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v1m6-11l-2-2M7 6l2-2m9 7h3m-12 0H2"/></svg>
    @else
        <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    @endif
    <div class="flex-1">
        <span class="font-semibold">{{ $message }}</span>
        @if($details ?? false)
            <p class="text-sm mt-1 opacity-90">{{ $details }}</p>
        @endif
    </div>
</div>
