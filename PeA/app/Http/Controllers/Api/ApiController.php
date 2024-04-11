<?php
#TODO:IMPLEMENT EMAIL VERIFICATION, EMAIL NOTIFICATIONS AND PASSWORD RESETS
#EMAIL VERIFICATIONS SHOULD BE EASY,SO START BY THERE
#THEN PASSWORD RESETS
#THEN EMAIL NOTIFS

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LostObject;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use PeA\database\factories\UserFactory;
use PHPUnit\Metadata\Uses;

class ApiController extends Controller
{

public function index() {
    $user =  User::all();
    return view('users' ,['users' => $user]);
}

public function register(Request $request){

    try {
       $val =  $request->validate([
            'name' => 'required|string',
            'gender' => 'required|string',
            'birthdate' => 'required|date',
            'address' => 'required|string',
            'codigo_postal' => 'required|string',
            'localidade' => 'required|string',
            'civilId' => 'required|string|unique:users',
            'taxId' => 'required|string|unique:users',
            'contactNumber' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);
        
        var_dump($val);

        $uuid = (string) Str::uuid();

        $user = User::create([
            "account_id" => $uuid,
            "name" => $request-> input('name'),
            "gender" => $request->input('gender'),
            "birthdate" => $request->input('birthdate'),
            "address" => $request->input('address'),
            "codigo_postal" => $request->input('codigo_postal'),
            "localidade" => $request->input('localidade'),
            "civilId" => $request->input('civilId'),
            "taxId" => $request->input('taxId'),
            "contactNumber" => $request->input('contactNumber'),
            "email" => $request->input('email') ,
            "password" => Hash::make($request->input('password')),
            "account_status" => 'active',
            "token" => '',
            "email_verified_at" => '',
            "bid_history" => [],
            "lost_objects" => [],
        ]);
     

        return response()->json([
            "status" => true,
            "message" => "Utilizador registado com sucesso",
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

        if ($e->errors()['civilId'] && $e->errors()['civilId'][0] === 'Cartão de cidadão já associado a outra conta.') {
            return response()->json([
                "status" => false,
                "message" => "Cartão de cidadão já associado a outra conta.",
                "code" => "400",
            ], 400);
        }
   
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
                
                $expirationTime = now()->addHours(24);

                $token = $user->createToken(name: 'personal-token', expiresAt: now()->addMinutes(30))->plainTextToken;

                $user->update(['token' => explode('|', $token)[1]]);

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
                "message" => "Conta desativada com sucesso",
            ]);
        } else {
            return response()->json([
                "status" => false,
                "code" => 404,
                "message" => "Conta inválida.",
            ], 404);
        }
    }

        //activate account 
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
                "message" => "Conta reativada com sucesso.",
            ]);
        } else {
            return response()->json([
                "status" => false,
                "code" => 404,
                "message" => "Conta inválida.",
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


public function lostObjects(Request $request){
    try {
        $request->validate([
            'email' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                "status" => false,
                "message" => "User not found.",
                "code" => 404,
            ], 404);
        }

        // Get the array of lost object IDs for the user
        $lostObjectIds = $user->lost_objects;

        // Retrieve the lost objects from the database
        $lostObjects = LostObject::whereIn('lostObjectId', $lostObjectIds)->get();

        $response = [
            "status" => true,
            "message" => "Lost objects retrieved successfully.",
            "lost_objects" => $lostObjects,
        ];

        return response()->json($response, 200);

    } catch (\Exception $e) {
        return response()->json([
            "status" => false,
            "message" => "An error occurred while retrieving lost objects.",
            "code" => 500,
        ], 500);
    }
}

public function myBids(Request $request){
    try {
        $request->validate([
            'email' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                "status" => false,
                "message" => "Utilizador não encontrado.",
                "code" => 404,
            ], 404);
        }

        // Get the array of lost object IDs for the user
        $bidIds = $user->bid_history;

        // Retrieve the lost objects from the database
        $bids = Bid::whereIn('bidId', $bidIds)->get();

        $response = [
            "status" => true,
            "message" => "Licitações retornadas.",
            "lost_objects" => $bids,
        ];

        return response()->json($response, 200);

    } catch (\Exception $e) {
        return response()->json([
            "status" => false,
            "message" => "Ocorreu um erro ao tentar recuperar a lista de licitações.",
            "code" => 500,
        ], 500);
    }
}

}


=======
// Verify email method
public function verify(Request $request)
{
    $request->validate([
        'id' => 'required|exists:users,id',
        'hash' => 'required|string',
    ]);

    $user = User::findOrFail($request->id);

    if (!hash_equals((string)$request->hash, sha1($user->getEmailForVerification()))) {
        return response()->json(['message' => 'Email verification failed'], 403);
    }

    if ($user->hasVerifiedEmail()) {
        return response()->json(['message' => 'Email already verified'], 400);
    }

    $user->markEmailAsVerified();

    event(new Verified($user));

    return response()->json(['message' => 'Email verified successfully'], 200);
}

// Forgot password method
public function forgotPassword(Request $request)
{
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink($request->only('email'));

    return $status === Password::RESET_LINK_SENT
        ? response()->json(['message' => 'Password reset link sent'], 200)
        : response()->json(['message' => 'Unable to send password reset link'], 400);
}


    // Reset password method
    public function resetPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'token' => 'required|string',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill(['password' => Hash::make($password)])->save();
            event(new PasswordReset($user));
        }
    );

    return $status == Password::PASSWORD_RESET
        ? response()->json(['message' => __($status)], 200)
        : response()->json(['message' => __($status)], 400);
}



public function sendResetLinkEmail(Request $request)
{
    $request->validate([
        'email' => 'required|email',
    ]);

    $status = Password::sendResetLink($request->only('email'));

    return $status === Password::RESET_LINK_SENT
        ? redirect()->back()->with('status', trans($status))
        : back()->withErrors(['email' => trans($status)]);
}

public function destroy(string $id) {
    User::where('_id' ,$id )->delete();
    return redirect()->route('users.store');
}

}

