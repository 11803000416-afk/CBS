<?php

namespace App\Notifications;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BookingConfirmed extends Notification
{
    use Queueable;

    public function __construct(private Booking $booking, private string $recipientType = 'seller')
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        if ($this->recipientType === 'seller') {
            return $this->toSellerMail($notifiable);
        } elseif ($this->recipientType === 'admin') {
            return $this->toAdminMail($notifiable);
        } else {
            return $this->toBuyerMail($notifiable);
        }
    }

    private function toSellerMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Test Drive Booking Confirmed ✅')
            ->greeting('Hello ' . $this->booking->seller->name . '!')
            ->line('Your test drive booking has been confirmed!')
            ->line('**Booking Details:**')
            ->line('Vehicle: ' . $this->booking->vehicle->brand . ' ' . $this->booking->vehicle->model . ' (' . $this->booking->vehicle->year . ')')
            ->line('Buyer: ' . $this->booking->buyer->name)
            ->line('Buyer Email: ' . $this->booking->buyer->email)
            ->line('Buyer Phone: ' . ($this->booking->buyer->phone ?? 'N/A'))
            ->line('Test Drive Date: ' . $this->booking->booking_date->format('M d, Y'))
            ->line('Test Drive Time: ' . $this->booking->booking_time)
            ->line('Duration: ' . $this->booking->duration_hours . ' hour(s)')
            ->line('Total Amount: Nu. ' . number_format($this->booking->total_amount, 2))
            ->line('Buyer Message: ' . ($this->booking->buyer_message ?? 'No message provided'))
            ->action('View Booking Details', route('bookings.show', $this->booking))
            ->line('Please be prepared for the test drive at the scheduled time.')
            ->line('Thank you for using the Car Broker System!');
    }

    private function toBuyerMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Test Drive Booking Confirmed ✅')
            ->greeting('Hello ' . $this->booking->buyer->name . '!')
            ->line('Your test drive booking has been confirmed!')
            ->line('**Booking Details:**')
            ->line('Vehicle: ' . $this->booking->vehicle->brand . ' ' . $this->booking->vehicle->model . ' (' . $this->booking->vehicle->year . ')')
            ->line('Seller: ' . $this->booking->seller->name)
            ->line('Seller Email: ' . $this->booking->seller->email)
            ->line('Seller Phone: ' . $this->booking->seller->phone)
            ->line('Test Drive Date: ' . $this->booking->booking_date->format('M d, Y'))
            ->line('Test Drive Time: ' . $this->booking->booking_time)
            ->line('Duration: ' . $this->booking->duration_hours . ' hour(s)')
            ->line('Total Amount: Nu. ' . number_format($this->booking->total_amount, 2))
            ->action('View Booking Details', route('bookings.show', $this->booking))
            ->line('Please arrive on time for your test drive.')
            ->line('Thank you for using the Car Broker System!');
    }

    private function toAdminMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Test Drive Booking Notification 📋')
            ->greeting('Hello Admin!')
            ->line('A new test drive booking has been created.')
            ->line('**Booking Details:**')
            ->line('Booking ID: #' . $this->booking->id)
            ->line('Vehicle: ' . $this->booking->vehicle->brand . ' ' . $this->booking->vehicle->model . ' (' . $this->booking->vehicle->year . ')')
            ->line('Price: Nu. ' . number_format($this->booking->vehicle->price))
            ->line('Buyer: ' . $this->booking->buyer->name)
            ->line('Buyer Email: ' . $this->booking->buyer->email)
            ->line('Buyer Phone: ' . ($this->booking->buyer->phone ?? 'N/A'))
            ->line('Seller: ' . $this->booking->seller->name)
            ->line('Seller Email: ' . $this->booking->seller->email)
            ->line('Test Drive Date: ' . \Carbon\Carbon::parse($this->booking->booking_date)->format('M d, Y'))
            ->line('Test Drive Time: ' . $this->booking->booking_time)
            ->line('Status: ' . ucfirst($this->booking->status))
            ->line('Buyer Message: ' . ($this->booking->buyer_message ?? 'No message provided'))
            ->action('View Booking', route('bookings.show', $this->booking))
            ->line('Thank you for using the Car Broker System!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'vehicle_id' => $this->booking->vehicle_id,
            'booking_date' => $this->booking->booking_time,
        ];
    }
}
