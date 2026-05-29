<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class ChatController extends Controller
{
    /**
     * Show the chat interface.
     */
    public function index(): View
    {
        $currentUser = auth()->user();
        $chatPartners = ChatMessage::getChatPartners($currentUser->id);
        $unreadCount = ChatMessage::unreadCount($currentUser->id);

        return view('chat.index', compact('chatPartners', 'unreadCount'));
    }

    /**
     * Get or start a chat with a specific user.
     */
    public function show(User $user): View
    {
        $currentUser = auth()->user();

        if ($currentUser->id === $user->id) {
            return redirect()->route('chat.index');
        }

        // Load conversation
        $messages = ChatMessage::betweenUsers($currentUser->id, $user->id)
            ->latest('created_at')
            ->paginate(50);

        // Mark as read
        ChatMessage::where('from_user_id', $user->id)
            ->where('to_user_id', $currentUser->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        $chatPartners = ChatMessage::getChatPartners($currentUser->id);
        $unreadCount = ChatMessage::unreadCount($currentUser->id);

        return view('chat.show', compact('user', 'messages', 'chatPartners', 'unreadCount'));
    }
    
    /**
     * Handle non-existent user ID by redirecting to chat index
     */
    public function handleMissingUser()
    {
        return redirect()->route('chat.index')->with('error', 'User not found. Please select a chat partner.');
    }

    /**
     * Send a message (AJAX).
     */
    public function store(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000', 'min:1'],
        ]);

        $currentUser = auth()->user();

        if ($currentUser->id === $user->id) {
            return response()->json(['error' => 'Cannot message yourself'], 422);
        }

        $message = ChatMessage::create([
            'from_user_id' => $currentUser->id,
            'to_user_id' => $user->id,
            'message' => $validated['message'],
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'from_user_id' => $message->from_user_id,
                'to_user_id' => $message->to_user_id,
                'message' => $message->message,
                'is_read' => $message->is_read,
                'created_at' => $message->created_at->format('H:i'),
                'created_at_full' => $message->created_at->format('M d, Y H:i'),
            ],
        ]);
    }

    /**
     * Get messages for real-time chat (AJAX).
     */
    public function fetchMessages(Request $request, User $user): JsonResponse
    {
        $currentUser = auth()->user();
        $lastMessageId = $request->input('last_id', 0);

        $messages = ChatMessage::betweenUsers($currentUser->id, $user->id)
            ->where('id', '>', $lastMessageId)
            ->latest('created_at')
            ->get()
            ->reverse()
            ->values();

        // Mark new messages as read
        ChatMessage::where('from_user_id', $user->id)
            ->where('to_user_id', $currentUser->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json([
            'messages' => $messages->map(fn ($msg) => [
                'id' => $msg->id,
                'from_user_id' => $msg->from_user_id,
                'from_user_name' => $msg->fromUser->name,
                'to_user_id' => $msg->to_user_id,
                'message' => $msg->message,
                'is_read' => $msg->is_read,
                'created_at' => $msg->created_at->format('H:i'),
                'created_at_full' => $msg->created_at->format('M d, Y H:i'),
            ]),
        ]);
    }

    /**
     * Get chat partners with last message preview (AJAX).
     */
    public function getChatPartners(): JsonResponse
    {
        $currentUser = auth()->user();
        $partners = ChatMessage::getChatPartners($currentUser->id);

        return response()->json([
            'partners' => $partners->map(fn ($partner) => [
                'id' => $partner->id,
                'name' => $partner->name,
                'avatar_initials' => strtoupper(substr($partner->name, 0, 1)),
                'unread_count' => ChatMessage::where('from_user_id', $partner->id)
                    ->where('to_user_id', $currentUser->id)
                    ->where('is_read', false)
                    ->count(),
            ]),
        ]);
    }

    /**
     * Mark all messages from a user as read.
     */
    public function markAsRead(User $user): JsonResponse
    {
        $currentUser = auth()->user();

        ChatMessage::where('from_user_id', $user->id)
            ->where('to_user_id', $currentUser->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * Get unread count for current user.
     */
    public function unreadCount(): JsonResponse
    {
        $count = ChatMessage::unreadCount(auth()->id());

        return response()->json(['unread_count' => $count]);
    }

    /**
     * Delete a message (soft delete for privacy).
     */
    public function delete(ChatMessage $message): JsonResponse
    {
        if ($message->from_user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $message->update(['message' => '[Message deleted]']);

        return response()->json(['success' => true]);
    }

    /**
     * Get all users available for chat (except current user).
     */
    public function availableUsers(): View
    {
        $currentUser = auth()->user();
        $users = User::where('id', '!=', $currentUser->id)
            ->orderBy('name')
            ->get();

        $chatPartners = ChatMessage::getChatPartners($currentUser->id);
        $unreadCount = ChatMessage::unreadCount($currentUser->id);

        return view('chat.users', compact('users', 'chatPartners', 'unreadCount'));
    }
}
