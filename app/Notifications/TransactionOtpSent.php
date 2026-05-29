<?php

namespace App\Notifications;

use App\Models\TransactionOtp;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TransactionOtpSent extends Notification
{
    use Queueable;

    public TransactionOtp $otp;

    public function __construct(TransactionOtp $otp)
    {
        $this->otp = $otp;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $transaction = $this->otp->transaction;

        return (new MailMessage)
            ->subject('CBS Transaction OTP')
            ->greeting('Hello,')
            ->line('A transaction OTP has been generated for transaction ID: ' . $transaction->id)
            ->line('OTP Code: ' . $this->otp->code)
            ->line('This code will expire at ' . $this->otp->expires_at->toDayDateTimeString())
            ->line('If you did not initiate this, please contact CBS admin.')
            ->salutation('Regards, CBS');
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'OTP Sent',
            'message' => 'A transaction OTP was sent for transaction #' . $this->otp->transaction_id . '.',
            'url' => route('transactions.index'),
            'category' => 'otp',
            'transaction_id' => $this->otp->transaction_id,
        ];
    }
}
