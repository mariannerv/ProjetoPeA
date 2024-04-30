<?php

namespace App\Http\Controllers\Emails;

use Illuminate\Http\Request;
use App\Mail\SampleMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\welcome;
use App\Http\Controllers\Controller;

class SendMailController extends Controller
{
    public function sendWelcomeEmail($toEmail, $message, $subject){

        $response =  Mail::to($toEmail)->send(new welcome($message, $subject));
        

    }

}
