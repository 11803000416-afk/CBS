<?php

namespace App\Notifications;

use App\Models\Inquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InquiryResponded extends Notification
{
    use Queueable;

    public function __construct(private Inquiry $inquiry)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Inquiry Response Received - CBS')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your inquiry has received a response.')
            ->line('Vehicle: ' . ($this->inquiry->vehicle?->brand ?? 'Vehicle') . ' ' . ($this->inquiry->vehicle?->model ?? ''))
            ->action('View Inquiry', route('inquiries.index'))
            ->line('Thank you for using CBS.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Inquiry Response Received',
            'message' => 'Your inquiry has a new response from the broker/seller.',
            'url' => route('inquiries.index'),
            'category' => 'inquiry',
            'inquiry_id' => $this->inquiry->id,
        ];
    }
}
