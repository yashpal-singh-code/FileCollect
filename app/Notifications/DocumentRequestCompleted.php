<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentRequestCompleted extends Notification
{
    use Queueable;

    protected $documentRequest;

    public function __construct($documentRequest)
    {
        $this->documentRequest = $documentRequest;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Document Request Completed')
            ->line('All documents have been uploaded.')
            ->line('Request Number: ' . $this->documentRequest->request_number)
            ->action('View Request', url('/document-requests/' . $this->documentRequest->id));
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Document Request Completed',
            'request_number' => $this->documentRequest->request_number,
            'document_request_id' => $this->documentRequest->id,
        ];
    }
}
