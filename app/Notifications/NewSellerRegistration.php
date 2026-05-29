<?php

namespace App\Notifications;

use App\Models\Seller;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewSellerRegistration extends Notification
{
    use Queueable;

    public function __construct(private Seller $seller)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Seller Registration - Approval Required')
            ->greeting('Hello Admin!')
            ->line('A new seller has registered in the Car Broker System.')
            ->line('**Seller Details:**')
            ->line('Name: ' . $this->seller->name)
            ->line('Email: ' . ($this->seller->email ?? 'N/A'))
            ->line('Phone: ' . $this->seller->phone)
            ->line('Address: ' . ($this->seller->address ?? 'N/A'))
            ->line('Status: ' . ucfirst($this->seller->status))
            ->line('Registration Date: ' . $this->seller->created_at->format('M d, Y - h:i A'))
            ->action('Review & Approve Seller', route('sellers.index'))
            ->line('Please review and approve this seller registration in the admin panel.')
            ->line('Thank you for managing the Car Broker System!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'seller_id' => $this->seller->id,
            'seller_name' => $this->seller->name,
            'seller_email' => $this->seller->email,
        ];
    }
}
