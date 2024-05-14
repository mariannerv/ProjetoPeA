<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EmailVerificationModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use App\Http\Controllers\VerificationEmailController;

class verificationCodeController extends Controller
{
    public function createCode($email){
        
        $uuid = (string) Str::uuid();
        $date = date("Y-m-d H:i:s");
        $add_min = date("Y-m-d H:i:s", strtotime($date . "+30 minutes"));
        $code = EmailVerificationModel::create([
            "user_email" => $email,
            "expiration_time" => $add_min,
            "uuid" => $uuid,
        ]);

        app(VerificationEmailController::class)->sendVerifyEmail(
            $email,
            "http://localhost:8000/verify-email/" . $uuid,
            "Confirme o seu email"
        );

    }

    public function verifyEmail($uuid)
        {
            
            $verificationRecord = EmailVerificationModel::where('uuid', $uuid)->first();

           
            if (!$verificationRecord) {
                abort(404, 'Verification record not found');
            }

            // Checkar se o tempo expirou
            if (Carbon::now() > $verificationRecord->expiration_time) {
                $verificationRecord->delete();
                return redirect()->route('tokenexpirou', ['uuid' => $uuid]);

            } else {
                $user = User::where('email', $verificationRecord->user_email)->first();
                
                $user->email_verified = "true";
                $user->save();

            
                $verificationRecord->delete();

                return redirect()->route('verificaemail', ['uuid' => $uuid]);

            }
            
        }
    
    public function geraNovoToken($uuid){
        $verificationRecord = EmailVerificationModel::where('uuid', $uuid)->first();
        $user_email = $verificationRecord->user_email;
        $uuidNovo = (string) Str::uuid();
        $date = date("Y-m-d H:i:s");
        $add_min = date("Y-m-d H:i:s", strtotime($date . "+30 minutes"));
        $code = EmailVerificationModel::create([
            "user_email" => $user_email,
            "expiration_time" => $add_min,
            "uuid" => $uuidNovo,
        ]);


        app(VerificationEmailController::class)->sendVerifyEmail(
            $email,
            "http://localhost:8000/verify-email/" . $uuid,
            "Confirme o seu email"
        );


        return redirect()->route('mail-template.novoemail');
    }

}