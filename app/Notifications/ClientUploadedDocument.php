<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ClientUploadedDocument extends Notification
{
    use Queueable;

    protected $documentRequest;
    protected $field;

    public function __construct($documentRequest, $field)
    {
        $this->documentRequest = $documentRequest;
        $this->field = $field;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Document Uploaded',
            'message' => "A document was uploaded for '{$this->field}'",
            'request_number' => $this->documentRequest->request_number,
            'document_request_id' => $this->documentRequest->id,
        ];
    }
}
