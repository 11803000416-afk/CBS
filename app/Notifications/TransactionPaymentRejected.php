<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TransactionPaymentRejected extends Notification
{
    use Queueable;

    public function __construct(private Transaction $transaction)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('CBS Payment Rejected')
            ->greeting('Hello!')
            ->line('Your vehicle payment request has been rejected by CBS admin.')
            ->line('Vehicle: ' . $this->transaction->vehicle->brand . ' ' . $this->transaction->vehicle->model)
            ->line('Please contact CBS support if you need help.')
            ->action('View Transactions', route('transactions.index'))
            ->line('Thank you for your patience.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Payment Rejected',
            'message' => 'Transaction #' . $this->transaction->id . ' payment request was rejected.',
            'url' => route('transactions.index'),
            'category' => 'approval',
            'transaction_id' => $this->transaction->id,
        ];
    }
}
