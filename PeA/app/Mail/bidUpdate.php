<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class bidUpdate extends Mailable
{
    use Queueable, SerializesModels;


    public $subject;
    public $valor;
    public $idLeilao;
    public $dataFim;


    public function __construct($valor, $idLeilao, $dataFim, $subject)
    {
        
        $this->subject = $subject;
        $this->valor = $valor;
        $this->idLeilao = $idLeilao;
        $this->dataFim = $dataFim;
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
            view: 'emails.bidUpdatemail',
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
