<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewBuyerRegistration extends Notification
{
    use Queueable;

    public function __construct(private User $buyer)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Buyer Registration - Approval Required')
            ->greeting('Hello Admin!')
            ->line('A new buyer has registered in the Car Broker System.')
            ->line('**Buyer Details:**')
            ->line('Name: ' . $this->buyer->name)
            ->line('Email: ' . $this->buyer->email)
            ->line('Phone: ' . ($this->buyer->phone ?? 'N/A'))
            ->line('Address: ' . ($this->buyer->address ?? 'N/A'))
            ->line('Registration Date: ' . $this->buyer->created_at->format('M d, Y - h:i A'))
            ->action('Review & Approve Buyer', route('buyers.index'))
            ->line('Please review and approve this buyer registration in the admin panel.')
            ->line('Thank you for managing the Car Broker System!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'buyer_id' => $this->buyer->id,
            'buyer_name' => $this->buyer->name,
            'buyer_email' => $this->buyer->email,
        ];
    }
}
