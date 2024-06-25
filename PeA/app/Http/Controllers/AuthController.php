<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $input = $request->all();

        if (\Auth::attempt(['email' => $input['email'],'password' => $input['password']])) {
            $user = \Auth::user();
            $token = $user->createToken('user')->accessToken;

            return response()->json(['token' => $token]);
        }
    }

    public function loginUserDetails()
    {
        return response()->json(['user' => \Auth::user()]);
    }
}
