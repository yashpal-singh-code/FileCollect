<?php

namespace App\Mail;

use App\Models\DocumentRequest;
use Illuminate\Mail\Mailables\Content;
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

    public function content(): Content
    {
        // 1) Use the correct relation: requester(), not user()
        $requester = $this->documentRequest->requester;

        // 2) Resolve owner (for multi‑tenant / SaaS logic)
        // If the requester is the owner, use them; otherwise use their owner
        $owner = $requester?->is_owner ? $requester : $requester?->owner;

        // 3) Build full name from first_name + last_name (your schema)
        $fullName = trim(
            ($requester?->first_name ?? '') . ' ' .
                ($requester?->last_name ?? '')
        );

        // 4) Return the view with real tenant data
        return new Content(
            view: 'emails.document-request',
            with: [
                'documentRequest' => $this->documentRequest,
                'link'            => $this->link,

                // Real, tenant-specific values
                'requesterName'  => $fullName ?: 'User',
                'requesterEmail' => $requester?->email,
                'companyName'    => $owner?->companySetting?->company_name,
            ],
        );
    }
}
