@extends('layouts.app')

@section('title', 'Buyers')
@section('subtitle', 'Manage buyer information and contacts')

@section('content')
<div class="flex justify-between items-center mb-6">
    <p class="text-sm text-gray-400">Total: <span class="font-semibold text-white">{{ $buyers->total() }}</span> buyers</p>
    <a href="{{ route('buyers.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg transition-all flex items-center gap-2 font-medium shadow-lg">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Buyer
    </a>
</div>

<div class="glass-card rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-white/10 border-b border-white/10">
                <tr>
                    <th class="px-4 py-4 text-left font-semibold text-gray-300">Name</th>
                    <th class="px-4 py-4 text-left font-semibold text-gray-300">Phone</th>
                    <th class="px-4 py-4 text-left font-semibold text-gray-300">Email</th>
                    <th class="px-4 py-4 text-left font-semibold text-gray-300">Status</th>
                    <th class="px-4 py-4 text-left font-semibold text-gray-300">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/10">
                @forelse($buyers as $buyer)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-4 py-4 font-semibold text-white">{{ $buyer->name }}</td>
                        <td class="px-4 py-4 text-gray-300">{{ $buyer->phone }}</td>
                        <td class="px-4 py-4 text-gray-300">{{ $buyer->email ?: '-' }}</td>
                        <td class="px-4 py-4">
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $buyer->status === 'active' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 'bg-gray-500/20 text-gray-400 border border-gray-500/30' }}">
                                {{ ucfirst($buyer->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('buyers.edit', $buyer) }}" class="text-blue-400 hover:text-blue-300 font-medium flex items-center gap-1 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('buyers.destroy', $buyer) }}" onsubmit="return confirm('Are you sure?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button class="text-red-400 hover:text-red-300 font-medium flex items-center gap-1 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-12 text-center text-gray-400">No buyer records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-6">{{ $buyers->links() }}</div>
@endsection
