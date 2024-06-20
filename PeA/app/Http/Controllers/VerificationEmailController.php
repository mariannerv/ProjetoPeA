<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\SampleMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerificationMail;
use App\Http\Controllers\Controller;


class VerificationEmailController extends Controller
{
    public function sendVerifyEmail($toEmail, $uuid, $subject){


        $response =  Mail::to($toEmail)->send(new EmailVerificationMail($uuid, $subject));
        

    }
}
