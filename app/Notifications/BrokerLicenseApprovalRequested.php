<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BrokerLicenseApprovalRequested extends Notification
{
    use Queueable;

    public function __construct(private User $broker)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Broker Dealer License Approval Required')
            ->greeting('Hello Admin!')
            ->line('A broker has submitted dealer license details and requires your approval.')
            ->line('Broker Name: ' . $this->broker->name)
            ->line('Broker Email: ' . $this->broker->email)
            ->line('Dealer License Number: ' . ($this->broker->dealer_license_number ?? 'Not provided'))
            ->action('Review Broker License', route('admin.broker-licenses.show', $this->broker))
            ->line('Please review and approve/reject this request from the admin panel.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Broker License Approval Required',
            'message' => 'A broker submitted dealer license details and needs admin review.',
            'url' => route('admin.broker-licenses.show', $this->broker),
            'category' => 'approval',
            'broker_id' => $this->broker->id,
            'broker_name' => $this->broker->name,
            'broker_email' => $this->broker->email,
            'dealer_license_status' => $this->broker->dealer_license_status,
        ];
    }
}
