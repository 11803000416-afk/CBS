<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(ChatMessage $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.' . $this->message->to_user_id),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'message' => [
                'id' => $this->message->id,
                'from_user_id' => $this->message->from_user_id,
                'to_user_id' => $this->message->to_user_id,
                'message' => $this->message->message,
                'is_read' => $this->message->is_read,
                'created_at' => $this->message->created_at->format('H:i'),
                'created_at_full' => $this->message->created_at->format('M d, Y H:i'),
                'from_user' => [
                    'id' => $this->message->fromUser->id,
                    'name' => $this->message->fromUser->name,
                ],
            ],
        ];
    }
}