<?php

namespace App\Http\Controllers\Emails;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;
use App\Mail\NotifMail;

class EmailController extends Controller
{
    public function sendWelcomeEmail($toEmail) {
        // $toEmail = "projetopea1@gmail.com";
        $message = "Bem vindo aos Perdidos e Achados! Obrigado por ter criado conta.";
        $subject = "Email de Boas Vindas";

        $response = Mail::to($toEmail)->send(new WelcomeEmail($message,$subject));

        dd($response);
    }
    public function sendNotifMail($toEmail, $message, $subject)
    {
        $response = Mail::to($toEmail)->send(new NotifMail($message, $subject));
        return $response;
    }
}
