<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TransactionOtpVerified extends Notification
{
    use Queueable;

    public function __construct(private Transaction $transaction)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Transaction OTP Verified',
            'message' => 'OTP verification is complete for transaction #' . $this->transaction->id . '. Awaiting admin payment review.',
            'url' => route('transactions.edit', $this->transaction),
            'category' => 'otp',
            'transaction_id' => $this->transaction->id,
        ];
    }
}
