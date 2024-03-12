<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
public function register(Request $request){

    try {
        $request->validate([
            'name' => 'required|string',
            'gender' => 'required|string',
            'birthdate' => 'required|date',
            'address' => 'required|string',
            'codigo_postal' => 'required|string',
            'localidade' => 'required|string',
            'civilId' => 'required|string',
            'taxId' => 'required|string|unique:users',
            'contactNumber' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            "name" => $request->name,
            "gender" => $request->gender,
            "birthdate" => $request->birthdate,
            "address" => $request->address,
            "codigo_postal" => $request->codigo_postal,
            "localidade" => $request->localidade,
            "civilId" => $request->civilId,
            "taxId" => $request->taxId,
            "contactNumber" => $request->contactNumber,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "account_status" => 'active',
            "token" => ''
        ]);

        return response()->json([
            "status" => true,
            "message" => "User registered successfully",
            "code" => "200",
        ]);
    } catch (ValidationException $e) {
        if ($e->errors()['taxId'] && $e->errors()['taxId'][0] === 'Número de contribuinte já associado a outra conta.') {
            return response()->json([
                "status" => false,
                "message" => "Número de contribuinte já associado a outra conta.",
                "code" => "400",
            ], 400);
        }
        if ($e->errors()['email'] && $e->errors()['email'][0] === 'Email já associado a outra conta.') {
            return response()->json([
                "status" => false,
                "message" => "Email já associado a outra conta.",
                "code" => "400",
            ], 400);
        }
        // If the validation error is related to the password, return an appropriate response
        if ($e->errors()['password']) {
            return response()->json([
                "status" => false,
                "message" => "Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one digit, and one special character.",
                "code" => "400",
            ], 400);
        }
        
        throw $e;
    }
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
        if ($user->account_status == 'active') {
            if (Hash::check($request->password, $user->password)) {
                // Create a new token
                $token = $user->createToken('login_token')->plainTextToken;

                // Extrair o token que é preciso para o Bearer Authentication
                $tokenString = explode('|', $token)[1];

                // Meter na bd do user
                $user->update(['token' => $tokenString]);

                $expirationTime = now()->addHours(24)->format('Y-m-d H:i:s');

                return response()->json([
                    "status" => true,
                    "code" => 200,
                    "message" => "Login successful!",
                    "token" => $tokenString,
                    "expiration_time" => $expirationTime,
                ]);
            } else {
                return response()->json([
                    "status" => false,
                    "code" => 401,
                    "message" => "Credenciais inválidas",
                ], 401);
            }
        } else {
            return response()->json([
                "status" => false,
                "code" => 403,
                "message" => "Esta conta está desativada.",
            ], 403);
        }
    } else {
        return response()->json([
            "status" => false,
            "code" => 404,
            "message" => "Utilizador não encontrado",
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
            "message" => "Perfil atualizado com sucesso.",
        ]);
    } else {
        return response()->json([
            "status" => false,
            "code" => 404,
            "message" => "Utilizador não encontrado.",
        ], 404);
    }
}
public function updatePost(Request $request) {
    try {
        // Validate the request data
        $request->validate([
            'sigla' => 'required|string', 
            'morada' => 'string', 
            'codigo_postal' => 'string', 
            'localidade' => 'string', // Remove 'required'
            'unidade' => 'string|unique:police_station,unidade,', // Remove 'required'
            'telefone' => 'string', 
            'fax' => 'string', 
            'email' => 'email', 
        ]);

       
        $post = PoliceStation::where('sigla', $request->sigla)->first();

        if (!$post) {
            return response()->json([
                "status" => false,
                "message" => "Post not found",
                "code" => "404",
            ], 404);
        }


        $post->fill($request->only([
            'morada',
            'codigo_postal',
            'localidade',
            'unidade',
            'telefone',
            'fax',
            'email',
        ]))->save();

        return response()->json([
            "status" => true,
            "message" => "Post updated successfully",
            "code" => "200",
        ]);
    } catch (\Exception $e) {
     
        return response()->json([
            "status" => false,
            "message" => "An error occurred while updating the post",
            "code" => "500",
        ], 500);
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
                "message" => "Utilizador apagado com sucesso.",
            ]);
        } else {
            return response()->json([
                "status" => false,
                "code" => 404,
                "message" => "Utilizador não encontrado.",
            ], 404);
        }
    }
}
