<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TransactionPaymentApproved extends Notification
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
            ->subject('CBS Payment Approved')
            ->greeting('Hello!')
            ->line('Your vehicle payment has been approved by CBS admin.')
            ->line('Vehicle: ' . $this->transaction->vehicle->brand . ' ' . $this->transaction->vehicle->model)
            ->line('Sale Price: Nu. ' . number_format((float) $this->transaction->sale_price, 2))
            ->line('Status: Completed')
            ->action('View Transaction', route('transactions.index'))
            ->line('Thank you for using CBS.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Payment Approved',
            'message' => 'Transaction #' . $this->transaction->id . ' payment was approved.',
            'url' => route('transactions.index'),
            'category' => 'approval',
            'transaction_id' => $this->transaction->id,
        ];
    }
}
