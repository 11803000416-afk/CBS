<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BrokerLicenseApprovalStatusUpdated extends Notification
{
    use Queueable;

    public function __construct(
        private string $status,
        private ?string $adminNotes = null
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toArray(object $notifiable): array
    {
        $isApproved = $this->status === 'approved';

        return [
            'title' => $isApproved ? 'Dealer License Approved' : 'Dealer License Update',
            'message' => $isApproved
                ? 'Your dealer license is approved. You can now deal in vehicles and transactions.'
                : 'Your dealer license review status is ' . ucfirst($this->status) . '.',
            'url' => $isApproved ? route('dashboard') : route('broker.license.show'),
            'category' => 'approval',
            'status' => $this->status,
            'admin_notes' => $this->adminNotes,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $subject = $this->status === 'approved'
            ? 'Your Dealer License Has Been Approved'
            : 'Dealer License Review Update';

        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your dealer license request has been reviewed by CBS admin.')
            ->line('Current status: ' . ucfirst($this->status));

        if (! empty($this->adminNotes)) {
            $mail->line('Admin notes: ' . $this->adminNotes);
        }

        if ($this->status === 'approved') {
            $mail->line('You can now deal with vehicles and transactions in the broker dashboard.')
                ->action('Open Dashboard', route('dashboard'));
        } else {
            $mail->line('Please update your dealer license details and resubmit for approval.')
                ->action('Update Dealer License', route('broker.license.show'));
        }

        return $mail;
    }
}
