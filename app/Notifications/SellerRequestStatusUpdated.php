<?php

namespace App\Notifications;

use App\Models\SellerRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SellerRequestStatusUpdated extends Notification
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
        $approved = $this->sellerRequest->status === 'approved';

        $mail = (new MailMessage)
            ->subject($approved ? 'Seller Request Approved' : 'Seller Request Update')
            ->greeting('Hello ' . $this->sellerRequest->user->name . '!')
            ->line('Your request to become a seller has been reviewed by CBS admin.')
            ->line('Current status: ' . ucfirst($this->sellerRequest->status));

        if ($approved) {
            $mail->line('You can now list vehicles as a seller.')
                ->action('Go to Dashboard', route('dashboard'));
        } else {
            $mail->line('Please update your request details and try again if needed.')
                ->action('View Request', route('vehicles.show', $this->sellerRequest->vehicle));
        }

        if ($this->sellerRequest->admin_notes) {
            $mail->line('Admin notes: ' . $this->sellerRequest->admin_notes);
        }

        return $mail;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->sellerRequest->status === 'approved' ? 'Seller Request Approved' : 'Seller Request Update',
            'message' => 'Your seller request status is ' . ucfirst($this->sellerRequest->status) . '.',
            'url' => route('vehicles.show', $this->sellerRequest->vehicle),
            'category' => 'approval',
            'seller_request_id' => $this->sellerRequest->id,
            'status' => $this->sellerRequest->status,
        ];
    }
}