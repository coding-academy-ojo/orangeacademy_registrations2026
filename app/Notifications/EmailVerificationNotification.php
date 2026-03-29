<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailVerificationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Orange Coding Academy - Email Verification')
            ->view('emails.verify-email', [
                'code' => $this->code,
                'user' => $notifiable
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Email verification code sent to ' . $notifiable->email,
        ];
    }
}