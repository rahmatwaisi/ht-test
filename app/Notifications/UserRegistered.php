<?php

namespace App\Notifications;

use App\Enums\NotificationChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserRegistered extends Notification implements ShouldQueue
{
    use Queueable;

    public const QUEUE = 'notifications:auth';

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        self::onQueue(self::QUEUE);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [NotificationChannel::MAIL->value];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('email.welcome_subject'))
            ->line(__('email.welcome_text'))
            ->action(__('email.continue'), url('/'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'email' => $notifiable->email
        ];
    }
}
