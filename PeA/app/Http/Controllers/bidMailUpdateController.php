<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\SampleMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\bidUpdate;


class bidMailUpdateController extends Controller
{
    public function sendBidEmail($toEmail, $subject, $emailContent){

        $response =  Mail::to($toEmail)->send(new bidUpdate($emailContent, $subject));

    }
}