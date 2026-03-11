<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;

class RegistrationSubmittedNotification extends Notification
{
    use Queueable;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Registration Submitted Successfully - Orange Coding Academy')
            ->view('emails.registration-submitted', [
                'user' => $this->user,
                'dashboard_url' => url('/student/dashboard')
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Registration Submitted',
            'message' => 'Your registration has been submitted successfully.',
            'action_url' => '/student/dashboard',
        ];
    }
}