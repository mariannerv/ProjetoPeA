<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PoliceStation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\PoliceController;
use App\Models\Police;
use App\Http\Controllers\Emails\SendMailController;
class PoliceStationController extends Controller
{
    private $sigla;
    public function index() {

        $user =  PoliceStation::all();
        return view('admin.stations' ,['users' => $user]);
    }



    public function registerPost(Request $request){

    try {
        $val = Validator::make($request->all(),[
        'morada' => 'required|string',
        'codigo_postal' => ['required', 'string', 'regex:/^\d{4}-\d{3}$/'],
        'localidade' => 'required|string',
        'unidade' => 'required|string|unique:police_station',
        'sigla' => 'required|string|unique:police_station',
        'telefone' => 'required|integer',
        'fax' => 'required|integer',
        'email' => 'required|email',
    ]);

    if ($val->fails()){
        return redirect()
        ->back()
        ->withErrors($val)
        ->withInput();
    }
    
    PoliceStation::create([
        "morada" => $request->input('morada') ,
        "codigo_postal" => $request->input('codigo_postal'),
        "localidade" => $request->input('localidade'),
        "unidade" => $request->input('unidade'),
        "sigla" => $request->input('sigla'),
        "telefone" => $request->input('telefone'),
        "fax" => $request->input('fax'),
        "email" => $request->input('email'),
    ]);

   
    return redirect()->route('register.success');

} catch (ValidationException $e) {
    if ($e->errors()['unidade'] && $e->errors()['unidade'][0] === 'Unidade já registada.') {
        return response()->json([
            "status" => false,
            "message" => "Posto já está registado.",
            "code" => "400",
        ], 400);
    }

    if ($e->errors()['sigla'] && $e->errors()['sigla'][0] === 'Sigla em uso.') {
    return response()->json([
        "status" => false,
        "message" => "Sigla já em uso.",
        "code" => "400",
    ], 400);
    }
}
}


    

public function reportadmin(Request $request, $policeStationId) {
    
    $validatedData = $request->validate([
        'textreport' => 'required|string',
    ], [
        'textreport.required' => 'Insira o seu report', 
        
    ]);


    $policeStationEmail = PoliceStation::where('sigla', $policeStationId)->value('email');

  
    if (!$policeStationEmail) {
        return back()->withErrors(['error' => 'E-mail da estação de polícia não encontrado.']);
    }

  
    app(SendMailController::class)->sendWelcomeEmail(
        $policeStationEmail,
        $validatedData['textreport'],
        $request->input('assunto')
    );

    return back()->with('success', 'E-mail enviado com sucesso.');
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

public function viewPost(Request $request)
    {
        try {
            $request->validate([
                'sigla' => 'required|string',
            ]);

            
            $post = PoliceStation::where('sigla', $request->sigla)->first();

            if ($post) {
              
                return response()->json([
                    "status" => true,
                    "data" => $post,
                    "code" => 200,
                ]);
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "Posto não encontrado",
                    "code" => 404,
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Ocorreu um erro ao recuperar as informações do posto",
                "code" => 500,
            ], 500);
        }
    }

public function deletePost(Request $request) {
    try {
        $sigla = $request->sigla;
        $post = PoliceStation::where('sigla', $sigla)->first();

        if ($post) {
            $post->delete();
            return response()->json([
                "status" => true,
                "message" => "Posto apagado com sucesso.",
                "code" => "200",
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Posto não encontrado.",
                "code" => "404",
            ], 404);
        }
    } catch (\Exception $e) {
        return response()->json([
            "status" => false,
            "message" => "Oops! Algo correu mal ao tentar apagar o posto.",
            "code" => "500",
        ], 500);
    }
}


public function sigla() {

    $user =  PoliceStation::all();
    return view('register.policesform' , ['users' => $user]);

}




public function destroy(string $id) {
    PoliceStation::where('_id' ,$id )->delete();
    return redirect()->route('stations.store');
}


public function edit(PoliceStation $user) {

    session(['sigla' => $user->sigla]);

    return view('profile.stations.partials.stationeditform' , ['user' => $user]);

    

}


public function update(Request $request, string $id) {

    $sigla = session('sigla');
    
    $update = PoliceStation::where('_id' , $id)->update($request->except(['_token' , '_method']));
    Police::where('policeStationId' , $sigla)->update(['policeStationId' => $request->sigla]);

    if ($update) {
        return redirect()->route('stations.store');
    }
}

}