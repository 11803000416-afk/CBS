<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class LiveChatController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        
        if ($user->hasRole(\App\Models\User::ROLE_SELLER)) {
            // Seller sees chat rooms for their vehicles
            $chatRooms = ChatRoom::with(['buyer', 'vehicle', 'messages' => function ($query) {
                return $query->latest()->limit(50);
            }])
                ->where('seller_id', $user->id)
                ->where('is_active', true)
                ->latest()
                ->get();
        } elseif ($user->hasRole(\App\Models\User::ROLE_BUYER)) {
            // Buyer sees chat rooms for their bookings
            $chatRooms = ChatRoom::with(['seller', 'vehicle', 'messages' => function ($query) {
                return $query->latest()->limit(50);
            }])
                ->where('buyer_id', $user->id)
                ->where('is_active', true)
                ->latest()
                ->get();
        } else {
            $chatRooms = collect();
        }

        return view('chat.index', compact('chatRooms'));
    }

    public function create(Vehicle $vehicle): JsonResponse
    {
        $buyer = auth()->user();
        $seller = $vehicle->created_by;

        // Check if chat room already exists
        $existingRoom = ChatRoom::where('vehicle_id', $vehicle->id)
                                      ->where('buyer_id', $buyer->id)
                                      ->where('seller_id', $seller->id)
                                      ->first();

        if ($existingRoom) {
            return response()->json([
                'success' => false,
                'message' => 'Chat room already exists for this vehicle'
            ]);
        }

        // Create new chat room
        $chatRoom = ChatRoom::create([
            'name' => "Chat for {$vehicle->brand} {$vehicle->model}",
            'vehicle_id' => $vehicle->id,
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Chat room created successfully!',
            'chat_room' => $chatRoom->load(['buyer', 'seller', 'vehicle'])
        ]);
    }

    public function show(ChatRoom $chatRoom): View
    {
        $this->authorize('view', $chatRoom);

        $messages = ChatMessage::with(['sender', 'receiver'])
            ->where('chat_room_id', $chatRoom->id)
            ->latest()
            ->paginate(50);

        // Mark messages as read
        ChatMessage::where('chat_room_id', $chatRoom->id)
                   ->where('receiver_id', auth()->id())
                   ->where('is_read', false)
                   ->update(['is_read' => true]);

        return view('chat.show', compact('chatRoom', 'messages'));
    }

    public function sendMessage(Request $request, ChatRoom $chatRoom): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = ChatMessage::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $this->getReceiverId($chatRoom),
            'message' => $request->message,
            'chat_room_id' => $chatRoom->id,
            'is_read' => false,
        ]);

        // Broadcast the Message
        event(new \App\Events\NewMessage($message));

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully!',
            'message_data' => $message->load(['sender', 'receiver'])
        ]);
    }

    public function getMessages(ChatRoom $chatRoom): JsonResponse
    {
        $messages = ChatMessage::with(['sender', 'receiver'])
            ->where('chat_room_id', $chatRoom->id)
            ->latest()
            ->paginate(50);

        return response()->json([
            'messages' => $messages,
            'pagination' => [
                'current_page' => $messages->currentPage(),
                'last_page' => $messages->lastPage(),
                'per_page' => $messages->perPage(),
                'total' => $messages->total(),
            ]
        ]);
    }

    private function getReceiverId(ChatRoom $chatRoom): int
    {
        $user = auth()->user();
        
        if ($user->id === $chatRoom->buyer_id) {
            return $chatRoom->seller_id;
        } else {
            return $chatRoom->buyer_id;
        }
    }
}
