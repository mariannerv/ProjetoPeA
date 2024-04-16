<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Police;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\PoliceStationController;
use App\Models\PoliceStation;

class PoliceController extends Controller
{
    public function index() {

        $user =  Police::all();
        return view('polices' ,['users' => $user]);
    }


    //Register
    public function registerPolicia(Request $request){
        try{
            $val = Validator::make($request->all(),[
                'name' => 'required|string',
                'internalId' => 'required|string|unique:police_user',
                'password' => 'required|min:8',
                'policeStationId' =>  'required|string|exists:police_station,sigla',
            ]); 


            if ($val->fails()){
                return redirect()
                ->back()
                ->withErrors($val)
                ->withInput();
            }
            



            Police::create([
                "name" => $request->input('name'),
                "internalId" => $request->input('internalId'),
                "password" => Hash::make($request->input('password')),
                "policeStationId" => $request->input('policeStationId'),
                "account_status" => 'active',
                "token" => '',
                "email_verified_at" => '',
            ]);

            return redirect()->route('registerSuccess');

        } catch (ValidationException $e){
            if ($e->errors()['internalId'] && $e->erros()['internalId'][0] === "Policia com este Id já associado a outra conta.");
                return response()->json([
                    "status" => false,
                    "message" => "Policia com este Id já associado a outra conta.",
                    "code" => 400,
                ]);
            
            throw $e;
        }
    }
    //Login
    public function loginPolice(Request $request)
{
    $request->validate([
        "internalId" => "required",
        "password" => "required",
    ]);

    $user = Police::where("internalId", $request->internalId)->first();

  if (!empty($user)) {
        if ($user->account_status == 'active') {
            if (Hash::check($request->password, $user->password)) {
                
             
                $expirationTime = now()->addHours(24);

                // Create the token instance
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
            "message" => "Policia não encontrado",
        ], 404);
    }
}

    //Profile
        public function profilePolice(){
        $data = auth()->user(); 

        return response()->json([
            "status" => true,
            "message" => "Police profile data",
            "user" => $data,
        ]);
    }



    //Edit
    public function updatePolice(Request $request)
    {
    $user = auth()->user();

    if ($user) {
        $request->validate([
            'name' => 'string',
            'internalId' => 'string',
            'policeStationId' => 'string',
            'password' => 'confirmed',
        ]);

        // Update the user's information based on the request data
        $user->update($request->all());

        return response()->json([
            "status" => true,
            "code" => 200,
            "message" => "Perfil de policia atualizado com sucesso.",
        ]);
    } else {
        return response()->json([
            "status" => false,
            "code" => 404,
            "message" => "Perfil de policia não encontrado.",
        ], 404);
    }
}

    //Deactivate
        public function deactivatePolice(Request $request)
    {
        $request->validate([
            "internalId" => "required",
            "password" => "required",
        ]);

        $user = Police::where("internalId", $request->internalId)->first();

        if ($user) {
            $user->account_status = 'deactivated';
            $user->save();

            return response()->json([
                "status" => true,
                "code" => 200,
                "message" => "Conta de policia desativada com sucesso",
            ]);
        } else {
            return response()->json([
                "status" => false,
                "code" => 404,
                "message" => "Conta de policia inválida.",
            ], 404);
        }
    }
    //Reactivate
        public function activatePolice(Request $request)
    {
        $request->validate([
            "internalId" => "required",
            "password" => "required",
        ]);

        $user = Police::where("internalId", $request->internalId)->first();

        if ($user) {
            $user->account_status = 'active';
            $user->save();

            return response()->json([
                "status" => true,
                "code" => 200,
                "message" => "Conta de policia reativada com sucesso.",
            ]);
        } else {
            return response()->json([
                "status" => false,
                "code" => 404,
                "message" => "Conta inválida.",
            ], 404);
        }
    }

    //Delete
        public function deletePolice(){
            $user = auth()->user();
                if ($user) {
            $user->delete();

            return response()->json([
                "status" => true,
                "code" => 200,
                "message" => "Policia apagado com sucesso.",
            ]);
        } else {
            return response()->json([
                "status" => false,
                "code" => 404,
                "message" => "Policia não encontrado.",
            ], 404);
        }
    }

    public function destroy(string $id) {
        Police::where('_id' ,$id )->delete();
        return redirect()->route('polices.store');
    }

    public function update(Request $request, string $id) {
        $update = Police::where('_id' , $id)->update($request->except(['_token' , '_method'])); 
        if ($update) {
            return redirect()->route('polices.store');
        }
    }

    public function edit(Police $user) {

        $sigla = PoliceStation::all();
        return view('policeseditform' , ['user' => $user , 'siglas' => $sigla]);

    }
}
