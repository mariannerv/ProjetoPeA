<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LostObject;
use App\Models\foundObjects;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use PeA\database\factories\UserFactory;
use PHPUnit\Metadata\Uses;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Emails\SendMailController;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Http\Controllers\verificationCodeController;



class ApiController extends Controller
{

public function index() {
    $user =  User::all();
    return view('admin.users' ,['users' => $user]);
}

public function register(Request $request){

    try {
        $val = Validator::make($request->all(),[
            'name' => ['required', 'string', 'max:255'],
            'gender' => 'required|string',
            'birthdate' => 'required|date',
            'address' => 'required|string',
            'codigo_postal' => 'required|string',
            'localidade' => 'required|string',
            'civilId' => 'required|string|unique:users',
            'taxId' => 'required|string|unique:users',
            'contactNumber' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => ['required', Rules\Password::defaults()],
        ]);
        
        if ($val->fails()){
            return redirect()
            ->back()
            ->withErrors($val)
            ->withInput();
        }

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
            "email_verified" => "false",
            "email_verified_at" => '',
            "bid_history" => [],
            "lost_objects" => [],
            "admin" => false,
        ]);
     
        event(new Registered($user));

        

        app(SendMailController::class)->sendWelcomeEmail(
            $request->input('email'),
            "Bem vindo ao PeA!",
            "Bem vindo!"
        );
        
        //cria logo um token pra verificar o email
        app(verificationCodeController::class)->createCode($request->input('email'));
        
        return redirect()->route('register.registerSuccess');

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
        $email = $request->input('email');
        $user = User::where("email", $email)->first();
        
      if (!empty($user)) {  
            if ($user->account_status == 'active') {
                if (Hash::check($request->input('password'), $user->password)) {
                    
                    $expirationTime = now()->addHours(24);
    
                   # $token = $user->createToken(name: 'personal-token', expiresAt: now()->addMinutes(30))->plainTextToken;
    
                    #$user->update(['token' => explode('|', $token)[1]]);
    
                    $expirationTime = now()->addHours(24)->format('Y-m-d H:i:s');
                    
                  
                    
                    Auth::loginUsingId($user->_id);

                    return view('home');
    
                   # return redirect()->route('userhome')->with('success' , 'Login');
                
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
        Auth::logout();

        return view('home');
    }

    public function update2(Request $request)
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

        $lostObjectIds = $user->lost_objects;

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

public function foundObjects(Request $request){
    try {
        $request->validate([
            'date_found' => 'required|string',
        ]);

        $user = User::where('_id', $request->id)->first();

        if (!$user) {
            return response()->json([
                "status" => false,
                "message" => "User not found.",
                "code" => 404,
            ], 404);
        }

        $foundObjectIds = $user->found_objects;

        $lfoundObjects = LostObject::whereIn('foundObjectId', $found_objects)->get();

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

        $bidIds = $user->bid_history;

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



// Verify email method




//___---------------------------------------------------------------

/* old destroy

public function destroy(string $id) {
    User::where('_id' ,$id )->delete();
    return redirect()->route('users.store');
}
*/
//confirm destroy method


public function confirmDelete(User $user)
{
    return view('profile.users.partials.confirm-deletion', compact('user'));
}


public function destroy(Request $request, $id)
{
    $user = User::findOrFail($id);
    
    if (Hash::check($request->password, $user->password)) {
        $user->delete();
        return redirect()->route('users.store')->with('success', 'User deleted successfully.');
    } else {
        return redirect()->route('users.store')->with('error', 'Incorrect password. User not deleted.');
    }
}
//___---------------------------------------------------------------
//___---------------------------------------------------------------


public function edit(User $user) {
    return view('profile.users.partials.usereditform' , ['user' => $user]);
}


public function update(Request $request, string $id) {
    $update = User::where('_id' , $id)->update(
        ["name" => $request-> input('name'),
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
            ]

    );
    
    if ($update) {
        return view('home');
    }
}


}
