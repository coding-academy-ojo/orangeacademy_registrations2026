<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
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
            ->subject('Registration Submitted Successfully - Orange Academy')
            ->greeting('Hello ' . ($this->user->profile->first_name_en ?? 'Student') . '!')
            ->line('You are successfully registered to Orange Coding Academy.')
            ->action('View Dashboard', url('/student/dashboard'))
            ->line('')
            ->line('If you have any questions, please don\'t hesitate to contact us.')
            ->line('Best regards,')
            ->line('Orange Academy Team');
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
