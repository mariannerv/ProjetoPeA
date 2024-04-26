<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EmailVerificationModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;

class verificationCodeController extends Controller
{
    public function createCode(Request $request){
        

        $date = date("Y-m-d H:i:s");
        $add_min = date("Y-m-d H:i:s", strtotime($date . "+30 minutes"));
        $length = 6;
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        $request->validate([
            'user_email' => 'required|string',
            
        ]);

        $code = EmailVerificationModel::create([
            "user_email" => $request->user_email,
            "code" => $randomString,
            "expiration_time" => $add_min,
        ]);
        
        return response()->json([
            "status" => true,
            "message" => "Código criado com sucesso",
            "code" => 200, 
        ]);
    }




}