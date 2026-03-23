<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DocumentRequestCompleted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $documentRequest;

    public function __construct($documentRequest)
    {
        $this->documentRequest = $documentRequest;
    }

    /**
     * Channels
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * ✅ Get Client Full Name
     */
    protected function getClientName()
    {
        if ($this->documentRequest->client) {
            return trim(
                ($this->documentRequest->client->first_name ?? '') . ' ' .
                    ($this->documentRequest->client->last_name ?? '')
            );
        }

        return 'Client';
    }

    /**
     * DATABASE NOTIFICATION
     */
    public function toDatabase($notifiable)
    {
        $clientName = $this->getClientName();

        return [
            'title' => '✅ Document Request Completed',
            'message' => "All documents uploaded by {$clientName}",
            'client_name' => $clientName,
            'request_number' => $this->documentRequest->request_number,
            'document_request_id' => $this->documentRequest->id,
        ];
    }

    /**
     * EMAIL (CUSTOM BLADE TEMPLATE)
     */
    public function toMail($notifiable)
    {
        $clientName = $this->getClientName();

        return (new MailMessage)
            ->subject("{$clientName}|document upload")
            ->view('emails.document_request_completed', [
                'clientName'     => $clientName,
                'recipientName'  => $notifiable->first_name ?? 'User',
                'requestNumber'  => $this->documentRequest->request_number,
                'url'            => route('document-requests.show', $this->documentRequest->id),
            ]);
    }
}
