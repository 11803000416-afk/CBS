<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TransactionPaymentRequested extends Notification
{
    use Queueable;

    public function __construct(private Transaction $transaction)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Payment Approval Required',
            'message' => 'Transaction #' . $this->transaction->id . ' is pending admin approval.',
            'url' => route('transactions.edit', $this->transaction),
            'category' => 'approval',
            'transaction_id' => $this->transaction->id,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('CBS Payment Approval Required')
            ->greeting('Hello CBS Admin!')
            ->line('A vehicle payment is waiting for your approval before confirmation is sent to the buyer and seller.')
            ->line('Vehicle: ' . $this->transaction->vehicle->brand . ' ' . $this->transaction->vehicle->model)
            ->line('Buyer: ' . $this->transaction->buyer->name)
            ->line('Seller: ' . $this->transaction->seller->name)
            ->line('Sale Price: Nu. ' . number_format((float) $this->transaction->sale_price, 2))
            ->line('Commission: Nu. ' . number_format((float) $this->transaction->broker_commission, 2))
            ->action('Review Transaction', route('transactions.edit', $this->transaction))
            ->line('Please approve or reject this payment request from the transactions page.');
    }
}
