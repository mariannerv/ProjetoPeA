<?php

namespace App\Http\Controllers\Emails;

use Illuminate\Http\Request;
use App\Mail\SampleMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\bidUpdate;
use App\Http\Controllers\Controller;

class bidMailUpdateController extends Controller
{
    public function sendBidEmail($toEmail, $subject, $valor, $idLeilao, $dataFim){

        $response =  Mail::to($toEmail)->send(new bidUpdate($valor, $idLeilao, $dataFim, $subject));

    }
}