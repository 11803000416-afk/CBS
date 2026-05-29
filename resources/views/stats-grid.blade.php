<!-- Professional Stats Grid Component -->
<!-- Usage: @include('stats-grid', ['stats' => $statsArray]) -->
@if($stats ?? false)
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    @foreach($stats as $stat)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 hover:border-blue-300 hover:shadow-md transition-all p-6 group">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br {{ $stat['color'] ?? 'from-blue-100 to-blue-200' }} flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                {{ $stat['icon'] ?? '📊' }}
            </div>
            @if($stat['trend'] ?? false)
            <div class="px-2.5 py-1 rounded-lg text-xs font-bold {{ $stat['trend'] > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                {{ $stat['trend'] > 0 ? '+' : '' }}{{ $stat['trend'] }}%
            </div>
            @endif
        </div>
        <p class="text-gray-600 text-sm font-medium mb-1">{{ $stat['label'] }}</p>
        <p class="text-3xl font-bold text-gray-900">{{ $stat['value'] }}</p>
        @if($stat['description'] ?? false)
        <p class="text-xs text-gray-500 mt-2">{{ $stat['description'] }}</p>
        @endif
    </div>
    @endforeach
</div>
@endif
