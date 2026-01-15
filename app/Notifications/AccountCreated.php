<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public string $password;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $password)
    {
        $this->password = $password;
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
            ->subject(__('mail.account_created_subject'))
            ->greeting(__('mail.hello'))
            ->line(__('mail.account_created_intro'))
            ->line(__('mail.email_label', ['email' => $notifiable->email]))
            ->line(__('mail.password_label', ['password' => $this->password]))
            ->action(__('mail.login_action'), route('login'))
            ->line(__('mail.change_password_notice'));
    }
}