<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class notifMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $mensagem;
    public $buttonUrl;

    /**
     * Create a new message instance.
     *
     * @param string $subject
     * @param string $mensagem
     * @param string $buttonUrl
     */
    public function __construct($subject, $mensagem, $buttonUrl)
    {
        $this->subject = $subject;
        $this->mensagem = $mensagem;
        $this->buttonUrl = $buttonUrl;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('projetopea1@gmail.com', 'Perdidos & Achados')
                    ->replyTo('projetopea1@gmail.com', 'Perdidos & Achados')
                    ->subject($this->subject)
                    ->view('emails.notification');
    }
}
