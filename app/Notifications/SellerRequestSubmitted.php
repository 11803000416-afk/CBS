<?php

namespace App\Notifications;

use App\Models\SellerRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SellerRequestSubmitted extends Notification
{
    use Queueable;

    public function __construct(private SellerRequest $sellerRequest)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $user = $this->sellerRequest->user;

        return (new MailMessage)
            ->subject('New Seller Request Submitted')
            ->greeting('Hello Admin!')
            ->line('A buyer has requested to become a seller.')
            ->line('Name: ' . $user->name)
            ->line('Email: ' . $user->email)
            ->line('Request Status: ' . ucfirst($this->sellerRequest->status))
            ->action('Review Seller Request', route('admin.seller-requests.show', $this->sellerRequest))
            ->line('Please review and approve or reject this request.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Seller Request Submitted',
            'message' => 'A buyer requested to become a seller.',
            'url' => route('admin.seller-requests.show', $this->sellerRequest),
            'category' => 'approval',
            'seller_request_id' => $this->sellerRequest->id,
            'user_id' => $this->sellerRequest->user_id,
            'user_name' => $this->sellerRequest->user->name,
            'user_email' => $this->sellerRequest->user->email,
        ];
    }
}