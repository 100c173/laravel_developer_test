<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public array $emailData)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->emailData['subject'] ?? 'Notification')
            ->greeting('Hello ' . $notifiable->name)
            ->line($this->emailData['message'] ?? 'You have a new notification.')
            ->when(
                isset($this->emailData['action_url']),
                fn($mail) => $mail->action(
                    $this->emailData['action_text'] ?? 'View',
                    $this->emailData['action_url']
                )
            )
            ->line('Thank you for using our application.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
