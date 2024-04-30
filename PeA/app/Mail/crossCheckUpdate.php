<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class crossCheckUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $mensagem;
    public $buttonUrl;
    

    /**
     * Create a new message instance.
     */
    public function __construct($subject, $mensagem, $buttonUrl)
    {
        $this->subject = $subject;
        $this->mensagem = $mensagem;
        $this->buttonUrl = $buttonUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('projetopea1@gmail.com', 'Perdidos & Achados'),
            replyTo:[
                new Address('projetopea1@gmail.com', 'Perdidos & Achados'),
            ],
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.crosscheck',
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
