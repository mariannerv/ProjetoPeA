<?php

namespace App\Http\Controllers\Emails;

use Illuminate\Http\Request;
use App\Mail\SampleMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\crossCheckUpdate;
use App\Http\Controllers\Controller;

class crossCheckMailController extends Controller
{
    public function sendCrossCheckEmail($toEmail, $subject, $mensagem){

        $buttonUrl = 'http://localhost:8000/users'; //só pra testar o button
        $response = Mail::to($toEmail)->send(new crossCheckUpdate($subject, $mensagem, $buttonUrl));
    }
}
