<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewMessage implements ShouldBroadcast
{
    use InteractsWithSockets;

    public function __construct(
        public ChatMessage $message
    ) {}

    public function broadcastOn(): Channel
    {
        return new PrivateChannel('chat.' . $this->message->chat_room_id);
    }

    public function broadcastAs(): string
    {
        return 'new.message';
    }
}
