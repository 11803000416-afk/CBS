@extends('layouts.app')

@section('title', 'Start New Chat')
@section('subtitle', 'Select a user to begin chatting')

@section('content')
<style>
    .users-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
    }

    @media (max-width: 768px) {
        .users-grid {
            grid-template-columns: 1fr;
        }
    }

    .user-card {
        glass-card rounded-xl p-6 border border-white/10 transition-all duration-300 hover:shadow-lg hover:border-cyan-500/30 cursor-pointer;
        animation: slideUp 0.6s ease-out;
    }

    .user-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(2, 132, 199, 0.2);
    }

    .user-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
    }

    .user-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0284c7 0%, #06b6d4 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: white;
        font-size: 18px;
    }

    .user-info {
        flex: 1;
    }

    .user-name {
        font-weight: 600;
        color: white;
        margin: 0;
    }

    .user-role {
        font-size: 12px;
        color: rgba(148, 163, 184, 0.8);
        margin: 4px 0 0 0;
    }

    .user-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        padding: 4px 8px;
        background: rgba(34, 197, 94, 0.2);
        border-radius: 4px;
        color: #22c55e;
    }

    .user-status.offline {
        background: rgba(107, 114, 128, 0.2);
        color: rgba(107, 114, 128, 0.8);
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .user-email {
        font-size: 13px;
        color: rgba(148, 163, 184, 0.7);
        margin-bottom: 12px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .chat-button {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        background: linear-gradient(135deg, #0284c7 0%, #06b6d4 100%);
        color: white;
        padding: 8px 12px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .chat-button:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);
    }

    .chat-button:active {
        transform: scale(0.98);
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .filter-section {
        display: flex;
        gap: 12px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 8px 16px;
        border-radius: 6px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.05);
        color: rgba(255, 255, 255, 0.8);
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        font-size: 13px;
    }

    .filter-btn:hover,
    .filter-btn.active {
        background: rgba(2, 132, 199, 0.3);
        border-color: #0284c7;
        color: white;
    }

    .search-box {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        padding: 10px 14px;
        color: white;
        font-size: 14px;
        margin-bottom: 24px;
        transition: all 0.3s ease;
    }

    .search-box:focus {
        outline: none;
        background: rgba(255, 255, 255, 0.15);
        border-color: #0284c7;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.1);
    }

    .search-box::placeholder {
        color: rgba(148, 163, 184, 0.6);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        background: rgba(2, 132, 199, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }

    .empty-state-text {
        color: rgba(148, 163, 184, 0.8);
    }
</style>

<div class="mb-8">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="p-3 bg-gradient-to-br from-cyan-500/20 to-blue-500/20 rounded-lg border border-cyan-500/30">
                <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 6a3 3 0 11-6 0 3 3 0 016 0zM6 20h12a6 6 0 00-6-6 6 6 0 00-6 6z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-white">Start New Chat</h2>
                <p class="text-sm text-gray-400 mt-1">Select a user to begin your conversation</p>
            </div>
        </div>
        <a href="{{ route('chat.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white/10 border border-white/20 rounded-lg text-gray-300 hover:text-white hover:bg-white/15 transition-all font-semibold">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back
        </a>
    </div>

    <!-- Search -->
    <input 
        type="text" 
        id="searchInput"
        class="search-box w-full" 
        placeholder="Search users by name, email, or role..."
    >

    <!-- Filters -->
    <div class="filter-section">
        <button class="filter-btn active" data-filter="all">All Users</button>
        @php
            $roles = \App\Models\User::distinct('role')->pluck('role');
        @endphp
        @foreach($roles as $role)
            <button class="filter-btn" data-filter="{{ $role }}">{{ ucfirst($role) }}</button>
        @endforeach
    </div>
</div>

@if($users->isEmpty())
    <div class="glass-card rounded-xl border border-white/10 p-12 text-center">
        <div class="empty-state-icon">
            <svg class="w-10 h-10 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 6a3 3 0 11-6 0 3 3 0 016 0zM6 20h12a6 6 0 00-6-6 6 6 0 00-6 6z"/>
            </svg>
        </div>
        <p class="text-white text-lg font-semibold mb-2">No Users Available</p>
        <p class="empty-state-text">There are no other users to chat with at the moment.</p>
    </div>
@else
    <div class="users-grid" id="usersContainer">
        @foreach($users as $availableUser)
            <div class="glass-card rounded-xl p-6 border border-white/10 hover:border-cyan-500/30 user-card-wrapper" data-role="{{ $availableUser->role }}" data-name="{{ $availableUser->name }}" data-email="{{ $availableUser->email }}">
                <div class="user-card-header">
                    <div class="user-avatar">
                        {{ strtoupper(substr($availableUser->name, 0, 1)) }}
                    </div>
                    <div class="user-info">
                        <h3 class="user-name">{{ $availableUser->name }}</h3>
                        <p class="user-role">{{ ucfirst($availableUser->role) }}</p>
                    </div>
                </div>
                <div class="user-email" title="{{ $availableUser->email }}">
                    {{ $availableUser->email }}
                </div>
                <div class="mb-3">
                    <span class="user-status">
                        <span class="status-dot"></span>
                        Online
                    </span>
                </div>
                <a href="{{ route('chat.show', $availableUser) }}" class="chat-button">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    Start Chat
                </a>
            </div>
        @endforeach
    </div>

    <div id="emptyState" class="glass-card rounded-xl border border-white/10 p-12 text-center hidden">
        <div class="empty-state-icon">
            <svg class="w-10 h-10 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        <p class="text-white text-lg font-semibold mb-2">No Users Found</p>
        <p class="empty-state-text">Try adjusting your search or filter criteria.</p>
    </div>
@endif

<script>
    const searchInput = document.getElementById('searchInput');
    const usersContainer = document.getElementById('usersContainer');
    const emptyState = document.getElementById('emptyState');
    const filterBtns = document.querySelectorAll('.filter-btn');
    let currentFilter = 'all';

    function filterUsers() {
        const searchTerm = searchInput.value.toLowerCase();
        const cards = document.querySelectorAll('.user-card-wrapper');
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.dataset.name.toLowerCase();
            const email = card.dataset.email.toLowerCase();
            const role = card.dataset.role.toLowerCase();

            const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
            const matchesFilter = currentFilter === 'all' || role === currentFilter;

            if (matchesSearch && matchesFilter) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Show/hide empty state
        if (visibleCount === 0 && cards.length > 0) {
            emptyState.classList.remove('hidden');
        } else {
            emptyState.classList.add('hidden');
        }
    }

    searchInput.addEventListener('input', filterUsers);

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentFilter = this.dataset.filter;
            filterUsers();
        });
    });
</script>
@endsection
