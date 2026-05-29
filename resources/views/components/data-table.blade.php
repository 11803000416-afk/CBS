<!-- Responsive Data Table Component -->
<div class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-xl border border-white/10 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-white/10 bg-white/5">
                    @foreach($columns as $key => $label)
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-300">{{ $label }}</th>
                    @endforeach
                    @if($actions ?? false)
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-300">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                        @foreach($columns as $key => $label)
                            <td class="px-6 py-4 text-sm text-gray-300">
                                @if(is_callable($callback ?? false))
                                    {{ $callback($item, $key) }}
                                @else
                                    {{ data_get($item, $key) }}
                                @endif
                            </td>
                        @endforeach
                        @if($actions ?? false)
                            <td class="px-6 py-4 text-sm">
                                {{ $actions($item) }}
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($columns) + (($actions ?? false) ? 1 : 0) }}" class="px-6 py-8 text-center text-gray-400">
                            No records found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
