<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNewUser extends Notification
{
    use Queueable;

    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Welcome to CBS — Account Created')
            ->greeting('Welcome, ' . ($this->user->name ?? ''))
            ->line('Thank you for creating an account on CBS — Car Broker System.')
            ->line('Please verify your email address to unlock all features.')
            ->action('Verify Email', url('/email/verify'))
            ->line('If you did not create this account, please contact support.');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Welcome to CBS — your account was created successfully.',
            'user_id' => $this->user->id ?? null,
        ];
    }
}
