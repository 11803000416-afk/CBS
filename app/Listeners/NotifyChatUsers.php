<?php

namespace App\Listeners;

use App\Events\NewMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyChatUsers implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(NewMessage $event): void
    {
        // Store the message in database and notify relevant users
        $message = $event->message;
        
        // Broadcast to all participants in the chat room
        broadcast(new NewMessage($message));
    }
}
