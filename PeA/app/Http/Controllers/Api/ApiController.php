<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class ApiController extends Controller
{
    //Register API (POST, formdata)
    public function register(Request $request){

        $request->validate([
            'name' => 'required|string',
            'gender' => 'required|string',
            'birthdate' => 'required|date',
            'address' => 'required|string',
            'civilId' => 'required|string',
            'taxId' => 'required|string',
            'contactNumber' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            
            
        ]);

        User::create([
            "name" => $request->name,
            "gender" => $request->gender,
            "birthdate" => $request->birthdate,
            "address" => $request->address,
            "civilId" => $request->civilId,
            "taxId" => $request->taxId,
            "contactNumber" => $request->contactNumber,
            "email" => $request->email,
            "password" => Hash::make($request->password),
        ]);

        return response()->json([
            "status" => true,
            "message" => "User registered successfully",
            "code" => "200",
        ]);
    }


    //Login API (POST, formdata)

public function login(Request $request)
{
    $request->validate([
        "email" => "required|email",
        "password" => "required",
    ]);

    $user = User::where("email", $request->email)->first();

    if (!empty($user)) {
        if (Hash::check($request->password, $user->password)) {
            $token = $user->createToken("userToken")->plainTextToken;

            return response()->json([
                "status" => true,
                "message" => "Login successful!",
                "token" => $token, 
            ]);
        }
    }

    return response()->json([
        "status" => false,
        "message" => "Invalid login details",
        "code" => 404,
    ]);
}


    // Profile API (GET)

    public function profile(){

    }

    // Logout API (GET)

    public function logout(){

    }
}
