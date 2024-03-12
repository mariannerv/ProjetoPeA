<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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
            "account_status" => 'active',
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
        if ($user->account_status == 'active') { // ver se a conta está ativa
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('user_token', ['*'], now()->addHours(24))->plainTextToken;
                $expirationTime = now()->addHours(24)->format('Y-m-d H:i:s');

                return response()->json([
                    "status" => true,
                    "code" => 200,
                    "message" => "Login successful!",
                    "token" => $token,
                    "expiration_time" => $expirationTime,
                ]);
            } else {
                return response()->json([
                    "status" => false,
                    "code" => 401,
                    "message" => "Invalid credentials",
                ], 401);
            }
        } else {
            return response()->json([
                "status" => false,
                "code" => 403,
                "message" => "Account is not active",
            ], 403);
        }
    } else {
        return response()->json([
            "status" => false,
            "code" => 404,
            "message" => "User not found",
        ], 404);
    }
}
    //Deactivate account 
    public function deactivate(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required",
        ]);

        $user = User::where("email", $request->email)->first();

        if ($user) {
            $user->account_status = 'deactivated';
            $user->save();

            return response()->json([
                "status" => true,
                "code" => 200,
                "message" => "User deactivated successfully",
            ]);
        } else {
            return response()->json([
                "status" => false,
                "code" => 404,
                "message" => "User not found",
            ], 404);
        }
    }

        //Deactivate account 
    public function activate(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required",
        ]);

        $user = User::where("email", $request->email)->first();

        if ($user) {
            $user->account_status = 'active';
            $user->save();

            return response()->json([
                "status" => true,
                "code" => 200,
                "message" => "User reactivated successfully",
            ]);
        } else {
            return response()->json([
                "status" => false,
                "code" => 404,
                "message" => "User not found",
            ], 404);
        }
    }

    // Profile API (GET)

    public function profile(){
        $data = auth()->user(); 

        return response()->json([
            "status" => true,
            "message" => "Profile data",
            "user" => $data,
        ]);
    }

    // Logout API (GET)

    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json([
            "status" => true,
            "message" => "User logged out",
        ]);
    }

    public function update(Request $request)
{
    $user = auth()->user();

    if ($user) {
        $request->validate([
            'name' => 'string',
            'gender' => 'string',
            'birthdate' => 'date',
            'address' => 'string',
            'civilId' => 'string',
            'taxId' => 'string',
            'contactNumber' => 'string',
            'email' => 'email|unique:users',
            'password' => 'confirmed',
        ]);

        // Update the user's information based on the request data
        $user->update($request->all());

        return response()->json([
            "status" => true,
            "code" => 200,
            "message" => "User profile updated successfully",
        ]);
    } else {
        return response()->json([
            "status" => false,
            "code" => 404,
            "message" => "User not found",
        ], 404);
    }
}
    
    //delete account
    public function delete(){
            $user = auth()->user();
                if ($user) {
            $user->delete();

            return response()->json([
                "status" => true,
                "code" => 200,
                "message" => "User deleted successfully",
            ]);
        } else {
            return response()->json([
                "status" => false,
                "code" => 404,
                "message" => "User not found",
            ], 404);
        }
    }
}
