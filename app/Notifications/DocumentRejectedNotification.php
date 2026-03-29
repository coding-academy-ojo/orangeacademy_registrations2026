<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Document;

class DocumentRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $document;
    protected $reason;

    /**
     * Create a new notification instance.
     */
    public function __construct(Document $document, string $reason)
    {
        $this->document = $document;
        $this->reason = $reason;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $docName = $this->document->requirement->name ?? 'Document';
        return (new MailMessage)
            ->subject('Action Required: Your Document was Rejected - Orange Coding Academy')
            ->view('emails.document-rejected', [
                'user' => $notifiable,
                'document_name' => $docName,
                'reason' => $this->reason,
                'update_url' => url('/student/registration/step/2')
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $docName = $this->document->requirement->name ?? 'Document';
        return [
            'title' => 'Document Rejected',
            'message' => 'Your document "' . $docName . '" was rejected. Reason: ' . $this->reason,
            'action_url' => '/student/registration/step/2',
            'document_id' => $this->document->id
        ];
    }
}
