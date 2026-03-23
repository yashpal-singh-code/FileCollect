<?php

namespace App\Mail;

use App\Models\DocumentRequest;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;

class DocumentRequestMail extends Mailable
{
    public DocumentRequest $documentRequest;
    public string $link;

    public function __construct(DocumentRequest $documentRequest, string $link)
    {
        $this->documentRequest = $documentRequest;
        $this->link = $link;
    }

    /**
     * ✅ SUBJECT (Laravel 10+ way)
     */
    public function envelope(): Envelope
    {
        $requester = $this->documentRequest->requester;

        $fullName = trim(
            ($requester?->first_name ?? '') . ' ' .
                ($requester?->last_name ?? '')
        );

        return new Envelope(
            subject: 'Document Request | '
                . ($fullName ?: 'User')
                . ' | '
                . $this->documentRequest->request_number,
        );
    }

    public function content(): Content
    {
        $requester = $this->documentRequest->requester;

        $owner = $requester?->is_owner ? $requester : $requester?->owner;

        $fullName = trim(
            ($requester?->first_name ?? '') . ' ' .
                ($requester?->last_name ?? '')
        );

        return new Content(
            view: 'emails.document-request',
            with: [
                'documentRequest' => $this->documentRequest,
                'link'            => $this->link,
                'requesterName'   => $fullName ?: 'User',
                'requesterEmail'  => $requester?->email,
                'companyName'     => $owner?->companySetting?->company_name,
            ],
        );
    }
}
