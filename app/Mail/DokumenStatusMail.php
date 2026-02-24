<?php

namespace App\Mail;

use App\Events\DokumenStatusChanged;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DokumenStatusMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $event;

    /**
     * Create a new message instance.
     */
    public function __construct(DokumenStatusChanged $event)
    {
        $this->event = $event;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subjects = [
            'approved' => '✅ Dokumen Anda Telah Disetujui',
            'rejected' => '❌ Dokumen Anda Ditolak',
            'revision' => '📝 Dokumen Anda Perlu Revisi',
            'pending' => '⏳ Status Dokumen Berubah',
        ];
        
        return new Envelope(
            subject: $subjects[$this->event->newStatus] ?? 'Pemberitahuan Status Dokumen',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.dokumen-status',
            with: [
                'dokumen' => $this->event->dokumen,
                'oldStatus' => $this->event->oldStatus,
                'newStatus' => $this->event->newStatus,
                'user' => $this->event->user,
                'timestamp' => $this->event->timestamp,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}