@extends('layouts.app')

@section('title', 'Chat with ' . $user->name)
@section('subtitle', 'Real-time conversation')

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

    .chat-header-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0284c7 0%, #06b6d4 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: white;
    }

    .chat-header-info h3 {
        font-weight: 600;
        color: white;
        margin: 0;
    }

    .chat-header-info p {
        font-size: 12px;
        color: rgba(148, 163, 184, 0.8);
        margin: 0;
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

    .send-button:hover:not(:disabled) {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);
    }

    .send-button:active:not(:disabled) {
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
</style>

<div class="mb-6">
    <a href="{{ route('chat.index') }}" class="inline-flex items-center gap-2 text-cyan-400 hover:text-cyan-300 font-semibold">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Chats
    </a>
</div>

<div class="chat-container glass-card">
    <!-- Sidebar with chat partners -->
    <div class="chat-sidebar">
        <div class="chat-sidebar-header">
            <div class="font-semibold text-white">Conversations</div>
        </div>
        
        @if($chatPartners->isEmpty())
            <div class="empty-state">
                <p class="text-gray-400 text-sm">No conversations yet</p>
            </div>
        @else
            @foreach($chatPartners as $partner)
                <a href="{{ route('chat.show', $partner) }}" class="chat-partner-item {{ $partner->id === $user->id ? 'active' : '' }} hover:bg-purple-500/20">
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
        <!-- Chat header -->
        <div class="chat-header">
            <div class="chat-header-title">
                <div class="chat-header-avatar">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="chat-header-info">
                    <h3>{{ $user->name }}</h3>
                    <p>{{ $user->role }}</p>
                </div>
            </div>
        </div>

        <!-- Messages area -->
        <div class="chat-messages" id="messagesContainer">
            @forelse($messages->reverse() as $message)
                <div class="message-group {{ $message->from_user_id === auth()->id() ? 'own' : '' }}">
                    <div class="message-avatar">
                        {{ strtoupper(substr($message->fromUser->name, 0, 1)) }}
                    </div>
                    <div class="message-content">
                        <div class="message-bubble">
                            {{ $message->message }}
                        </div>
                        <div class="message-time">
                            {{ $message->created_at->format('H:i') }}
                            @if($message->from_user_id === auth()->id() && $message->is_read)
                                <span class="message-status">✓ Read</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <svg class="w-12 h-12 text-gray-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <p class="text-gray-400 text-sm">No messages yet. Start the conversation!</p>
                </div>
            @endforelse
        </div>

        <!-- Input area - Read Only -->
        <div class="chat-input-area">
            <div class="bg-gray-100 rounded-lg p-4 text-center">
                <p class="text-gray-600 text-sm">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Messaging is currently disabled
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    const userId = {{ $user->id }};
    let lastMessageId = {{ $messages->last()?->id ?? 0 }};

    // Add message to DOM
    function addMessage(msg, isOwn) {
        const container = document.getElementById('messagesContainer');
        
        // Remove empty state if exists
        const emptyState = container.querySelector('.empty-state');
        if (emptyState) {
            emptyState.remove();
        }

        const messageDiv = document.createElement('div');
        messageDiv.className = `message-group ${isOwn ? 'own' : ''}`;
        messageDiv.innerHTML = `
            <div class="message-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="message-content">
                <div class="message-bubble">
                    ${msg.message}
                </div>
                <div class="message-time">
                    ${msg.created_at}
                </div>
            </div>
        `;

        container.appendChild(messageDiv);
    }

    // Scroll to bottom
    function scrollToBottom() {
        const container = document.getElementById('messagesContainer');
        container.scrollTop = container.scrollHeight;
    }

    // Fetch new messages periodically
    setInterval(async () => {
        try {
            const response = await fetch(`/chat/${userId}/fetch?last_id=${lastMessageId}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                }
            });

            const data = await response.json();

            if (data.messages && data.messages.length > 0) {
                data.messages.forEach(msg => {
                    addMessage(msg, msg.from_user_id === {{ auth()->id() }});
                    lastMessageId = Math.max(lastMessageId, msg.id);
                });
                scrollToBottom();
            }
        } catch (error) {
            console.error('Error fetching messages:', error);
        }
    }, 2000); // Poll every 2 seconds

    // Initial scroll
    document.addEventListener('DOMContentLoaded', scrollToBottom);
</script>
@endsection
