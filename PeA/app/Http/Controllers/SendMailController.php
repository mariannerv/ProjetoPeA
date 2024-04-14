<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\SampleMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\welcome;

class SendMailController extends Controller
{
    public function sendWelcomeEmail($toEmail){
        
        $message = "Bem vindo ao PeA!";
        $subject = 'Bem vindo!';

        $response =  Mail::to($toEmail)->send(new welcome($message, $subject));
        
        dd($response);

    }

}
