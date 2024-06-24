<?php


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
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Emails\SendMailController;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Http\Controllers\verificationCodeController;
use App\Http\Controllers\Police;



class ApiController extends Controller
{

public function index() {
    $user =  User::all();
    $numberUsers = User::count();
    $numberactive = User::where('account_status', 'active')->count();
    $deactivated = User::where('account_status', 'deactivated')->count();
    return view('admin.users' ,['users' => $user , 'numberusers' => $numberUsers , 
    'numberactive' => $numberactive , 'deactivated' => $deactivated]);
}

public function register(Request $request){

    try {
        $val = Validator::make($request->all(),[
            'name' => ['required', 'string', 'max:255'],
            'gender' => 'required|string',
            'birthdate' => 'required|date',
            'address' => 'required|string',
            'codigo_postal' => ['required', 'string', 'regex:/^\d{4}-\d{3}$/'],
            'localidade' => 'required|string',
            'civilId' => 'required|integer|unique:users',
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
        
        return redirect()->route('register.success');

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
                    Auth::loginUsingId($user->_id);
                    return redirect()->route('home')->with('success', 'Login realizado com sucesso!');
                } else {
                    return redirect()->back()->withErrors(['password' => 'Credenciais inválidas'])->withInput();
                }
            } else {
                return redirect()->back()->withErrors(['account_status' => 'Esta conta está desativada.'])->withInput();
            }
        } else {
            return redirect()->back()->withErrors(['email' => 'Utilizador não encontrado'])->withInput();
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
            return response()->json([ //projetopea1 ProjetoPea!
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

    public function report(Request $request) {
          // Validar que o campo textreport não está vazio
          $request->validate([
            'textreport' => 'required|string',
        ], [
            'textreport.required' => 'Insira o seu report', // Mensagem personalizada
        ]);

    // Se a validação passar, envie o e-mail
    if ($request->input('textreport') != "") {
        app(SendMailController::class)->sendWelcomeEmail(
            'projetopea1@outlook.com',
            $request->input('textreport'),
            'Bog na aplicação'
        );
    }

    // Redirecionar de volta com uma mensagem de sucesso
    return redirect()->back()->with('success', 'E-mail enviado com sucesso!');        


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
            'policeStationId' => 'required|string',
        ]);

        $user = Police::where('policeStationId', $request->policeStationId)->first();

        if (!$user) {
            return response()->json([
                "status" => false,
                "message" => "Police User not found.",
                "code" => 404,
            ], 404);
        }

        $foundObjectIds = $user->found_objects;

        $foundObjects = foundObject::whereIn('objectId', $foundObjectIds)->get();

        $response = [
            "status" => true,
            "message" => "found objects retrieved successfully.",
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

public function showactive() {
    $user = User::where('account_status', 'active')->get();
    $numberUsers = User::count();
    $numberactive = User::where('account_status', 'active')->count();
    $deactivated = User::where('account_status', 'deactivated')->count();
    return view('admin.users' ,['users' => $user , 'numberusers' => $numberUsers , 
    'numberactive' => $numberactive , 'deactivated' => $deactivated]);
}
public function showdeactivated() {
    $user = User::where('account_status', 'deactivated')->get();
    $numberUsers = User::count();
    $numberactive = User::where('account_status', 'active')->count();
    $deactivated = User::where('account_status', 'deactivated')->count();
    return view('admin.users' ,['users' => $user , 'numberusers' => $numberUsers , 
    'numberactive' => $numberactive , 'deactivated' => $deactivated]);
}
public function deactivateacount($id) {
    if (auth()->check()){
        if(auth()->user()->admin == "true") {
    //$userd = User::where('_id', $id)->get();
    $user = User::find($id);
    $user->account_status = 'deactivated';
    $user->save();

    app(SendMailController::class)->sendWelcomeEmail(
        $user->email,
        "Conta Destivada",
        "Sua conta foi destivada."
    );
    return redirect()->route("usersdeactivated.store");
}
    
}
return response()->json([
    "status" => false,
    "message" => "Não tem permição",
    "code" => 500,
], 500);
}

public function activeacount($id) {
    if (auth()->check()){
    if(auth()->user()->admin == "true") {

    
    //$userd = User::where('_id', $id)->get();
    $user = User::find($id);
    $user->account_status = 'active';
    $user->save();

    app(SendMailController::class)->sendWelcomeEmail(
        $user->email,
        "Conta Ativada",
        "Sua conta foi Ativada."
    );
    return redirect()->route("usersactive.store");
}
}

return response()->json([
    "status" => false,
    "message" => "Não tem permição",
    "code" => 500,
], 500);
}


public function showprofile($id) {
    $user = User::find($id);
    return view("admin.showprofile" , [ 'users' => $user]);
}


public function showreportadmin($id) {
    $user = User::find($id);
    return view("admin.admin-report" , [ 'users' => $user]);
}


public function reportadmin(Request $request , $email) {
      // Validar que o campo textreport não está vazio
      $request->validate([
        'textreport' => 'required|string',
    ], [
        'textreport.required' => 'Insira o seu report', // Mensagem personalizada
    ]);

// Se a validação passar, envie o e-mail
if ($request->input('textreport') != "") {
    app(SendMailController::class)->sendWelcomeEmail(
        $email,
        $request->input('textreport'),
        $request->input('assunto')
    );
}



// Redirecionar de volta com uma mensagem de sucesso
return redirect()->back()->with('success', 'E-mail enviado com sucesso!');        

}

public function addadmin($id) {

        if (auth()->check()){
        if (auth()->user()->email == "projetopea1@gmail.com") {
        
        $user = User::find($id);
        $user->admin = true;
        $user->save();
    
        app(SendMailController::class)->sendWelcomeEmail(
            $user->email,
            "Parabens voce foi promovido a administrador",
            "Promovido a moderador"
        );
        return redirect()->route("usersactive.store");
    }
}
return response()->json([
    "status" => false,
    "message" => "Não tem permição",
    "code" => 500,
], 500);
}

public function deladmin($id) {
    if (auth()->check()){
        if (auth()->user()->email == "projetopea1@gmail.com") {
    //$userd = User::where('_id', $id)->get();
    $user = User::find($id);
    $user->admin = false;
    $user->save();

    app(SendMailController::class)->sendWelcomeEmail(
        $user->email,
        "Voce foi despromovido",
        "Removido da administração"
    );
    return redirect()->route("usersactive.store");

}
    }
    return response()->json([
        "status" => false,
        "message" => "Não tem permição",
        "code" => 500,
    ], 500);
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
