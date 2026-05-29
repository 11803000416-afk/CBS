@extends('layouts.app')

@section('title', 'Live Chat')
@section('subtitle', 'Connect with other users in real-time')

@section('content')
<style>
    .chat-container {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 0;
        height: calc(100vh - 200px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        overflow: hidden;
    }

    @media (max-width: 768px) {
        .chat-container {
            grid-template-columns: 1fr;
            height: auto;
            min-height: 600px;
        }

        .chat-sidebar {
            display: none;
        }

        .chat-sidebar.mobile-active {
            display: block;
        }
    }

    .chat-sidebar {
        background: linear-gradient(135deg, rgba(139, 92, 246, 0.1) 0%, rgba(168, 85, 247, 0.05) 100%);
        border-right: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        flex-direction: column;
        backdrop-filter: blur(10px);
        overflow-y: auto;
    }

    .chat-sidebar-header {
        padding: 16px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .chat-partner-item {
        padding: 12px 16px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .chat-partner-item:hover {
        background-color: rgba(168, 85, 247, 0.2);
    }

    .chat-partner-item.active {
        background-color: rgba(168, 85, 247, 0.3);
        border-left: 3px solid #a855f7;
    }

    .partner-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: white;
        flex-shrink: 0;
    }

    .partner-info {
        flex: 1;
        min-width: 0;
    }

    .partner-name {
        font-weight: 600;
        color: white;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .partner-status {
        font-size: 12px;
        color: rgba(148, 163, 184, 0.8);
    }

    .unread-badge {
        background-color: #ef4444;
        color: white;
        font-size: 11px;
        padding: 2px 6px;
        border-radius: 10px;
        font-weight: bold;
    }

    .chat-main {
        display: flex;
        flex-direction: column;
        background: linear-gradient(135deg, rgba(2, 132, 199, 0.05) 0%, rgba(6, 182, 212, 0.05) 100%);
    }

    .chat-header {
        padding: 16px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(255, 255, 255, 0.05);
        display: flex;
        align-items: center;
        justify-content: space-between;
        backdrop-filter: blur(10px);
    }

    .chat-header-title {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .chat-header-info {
        font-weight: 600;
        color: white;
    }

    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 20px 16px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .message-group {
        display: flex;
        gap: 8px;
        animation: slideUp 0.3s ease-out;
    }

    .message-group.own {
        justify-content: flex-end;
    }

    .message-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0284c7 0%, #06b6d4 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: white;
        font-size: 12px;
        flex-shrink: 0;
    }

    .message-group.own .message-avatar {
        background: linear-gradient(135deg, #06b6d4 0%, #0284c7 100%);
        order: 2;
    }

    .message-content {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .message-group.own .message-content {
        align-items: flex-end;
    }

    .message-bubble {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        padding: 10px 14px;
        color: white;
        max-width: 400px;
        word-wrap: break-word;
        backdrop-filter: blur(10px);
    }

    .message-group.own .message-bubble {
        background: linear-gradient(135deg, rgba(2, 132, 199, 0.3) 0%, rgba(6, 182, 212, 0.3) 100%);
        border: 1px solid rgba(2, 132, 199, 0.3);
    }

    .message-time {
        font-size: 11px;
        color: rgba(148, 163, 184, 0.7);
        padding: 0 4px;
    }

    .message-status {
        font-size: 10px;
        color: rgba(148, 163, 184, 0.6);
    }

    .chat-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        gap: 16px;
    }

    .chat-empty-icon {
        width: 60px;
        height: 60px;
        background: rgba(2, 132, 199, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .chat-empty-text {
        color: rgba(148, 163, 184, 0.8);
        text-align: center;
    }

    .chat-input-area {
        padding: 16px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        display: flex;
        gap: 8px;
    }

    .chat-input-form {
        display: flex;
        gap: 8px;
        width: 100%;
    }

    .chat-input {
        flex: 1;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        padding: 10px 14px;
        color: white;
        resize: none;
        max-height: 100px;
        font-family: inherit;
        transition: all 0.3s ease;
    }

    .chat-input:focus {
        outline: none;
        background: rgba(255, 255, 255, 0.15);
        border-color: #0284c7;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.1);
    }

    .chat-input::placeholder {
        color: rgba(148, 163, 184, 0.6);
    }

    .send-button {
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #0284c7 0%, #06b6d4 100%);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 14px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .send-button:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);
    }

    .send-button:active {
        transform: scale(0.95);
    }

    .send-button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .empty-state {
        text-align: center;
        padding: 40px;
    }

    .new-chat-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%);
        color: white;
        padding: 10px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .new-chat-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(168, 85, 247, 0.3);
    }
</style>

<div class="mb-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="p-3 bg-gradient-to-br from-cyan-500/20 to-blue-500/20 rounded-lg border border-cyan-500/30">
                <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-white">Live Chat</h2>
                <p class="text-sm text-gray-400 mt-1">Real-time messaging with other users</p>
            </div>
        </div>
        <a href="{{ route('chat.users') }}" class="new-chat-btn">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Chat
        </a>
    </div>
</div>

<div class="chat-container glass-card">
    <!-- Sidebar with chat partners -->
    <div class="chat-sidebar">
        <div class="chat-sidebar-header">
            <div class="font-semibold text-white">Conversations</div>
            @if($unreadCount > 0)
                <span class="unread-badge">{{ $unreadCount }}</span>
            @endif
        </div>
        
        @if($chatPartners->isEmpty())
            <div class="empty-state">
                <svg class="w-12 h-12 text-gray-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <p class="text-gray-400 text-sm">No conversations yet</p>
                <a href="{{ route('chat.users') }}" class="text-cyan-400 hover:text-cyan-300 text-sm mt-2 inline-block">Start a chat</a>
            </div>
        @else
            @foreach($chatPartners as $partner)
                <a href="{{ route('chat.show', $partner) }}" class="chat-partner-item hover:bg-purple-500/20 transition-colors">
                    <div class="partner-avatar">{{ strtoupper(substr($partner->name, 0, 1)) }}</div>
                    <div class="partner-info">
                        <div class="partner-name">{{ $partner->name }}</div>
                        <div class="partner-status">{{ $partner->role }}</div>
                    </div>
                </a>
            @endforeach
        @endif
    </div>

    <!-- Main chat area -->
    <div class="chat-main">
        <div class="chat-empty">
            <div class="chat-empty-icon">
                <svg class="w-8 h-8 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
            <div class="chat-empty-text">
                <p class="font-semibold text-white">Welcome to Live Chat</p>
                <p class="text-sm">Select a conversation to start messaging or</p>
                <a href="{{ route('chat.users') }}" class="text-cyan-400 hover:text-cyan-300 font-semibold inline-block mt-2">start a new chat</a>
            </div>
        </div>
    </div>
</div>
@endsection
