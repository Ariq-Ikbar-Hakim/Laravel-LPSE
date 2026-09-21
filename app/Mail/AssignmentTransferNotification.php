<?php

namespace App\Mail;

use App\Models\AssignmentTransfer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AssignmentTransferNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public AssignmentTransfer $transfer,
        public string $event,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->event === 'submitted'
            ? 'Pengajuan Transfer Jabatan dan Paket Diterima'
            : 'Pengajuan Transfer Jabatan dan Paket Diperbarui');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.assignment-transfer');
    }

    public function attachments(): array
    {
        return [];
    }
}
