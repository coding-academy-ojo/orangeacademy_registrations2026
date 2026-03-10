<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminCredentialsNotification extends Notification
{
    use Queueable;

    protected $password;
    protected $role;

    public function __construct(string $password, string $role)
    {
        $this->password = $password;
        $this->role = $role;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $roleLabel = $this->role === 'super_admin' ? 'Super Admin' : 'Admin';
        
        return (new MailMessage)
            ->subject('Your Admin Account Has Been Created')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your admin account has been created by the system administrator.')
            ->line('Here are your login credentials:')
            ->line('**Email:** ' . $notifiable->email)
            ->line('**Temporary Password:** ' . $this->password)
            ->line('**Role:** ' . $roleLabel)
            ->action('Login to Dashboard', url('/admin/login'))
            ->line('For security reasons, please change your password after logging in.')
            ->line('Thank you!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Your admin account has been created with role: ' . $this->role,
        ];
    }
}
