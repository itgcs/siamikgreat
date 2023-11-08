<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SppMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;
    public $subject;
    public $pdf;
    
    /**
     * Create a new message instance.
     */
    public function __construct($mailData, $subject, $pdf)
    {
        $this->mailData = $mailData;
        $this->subject = $subject;
        $this->pdf = $pdf;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.spp-mail',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdf->output(), 'SPP '.date('F Y', strtotime($this->mailData['bill'][0]->created_at)). ' ' . $this->mailData['student']->name)
            ->withMime('application/pdf')
        ];
    }
}