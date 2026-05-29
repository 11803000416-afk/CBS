<!-- Empty State Component -->
<!-- Usage: @include('empty-state', ['icon' => '🚗', 'title' => 'No vehicles', 'message' => 'Start by adding...', 'button' => [...], 'illustration' => 'vehicle']) -->
<div class="bg-white rounded-2xl shadow-sm border border-dashed border-gray-300 p-12 text-center">
    <!-- Large Icon -->
    <div class="text-6xl mb-6 opacity-50">{{ $icon ?? '📦' }}</div>
    
    <!-- Content -->
    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $title ?? 'No data' }}</h3>
    <p class="text-gray-600 text-lg mb-8 max-w-md mx-auto">{{ $message ?? 'Get started by creating your first item' }}</p>
    
    <!-- Action Button -->
    @if($button ?? false)
        <a href="{{ $button['href'] }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all shadow-sm hover:shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ $button['text'] ?? 'Create New' }}
        </a>
    @endif
</div>
