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

class PoliceStationController extends Controller
{
    private $sigla;
    public function index() {

        $user =  PoliceStation::all();
        return view('stations' ,['users' => $user]);
    }



    public function registerPost(Request $request){

    try {
        $val = Validator::make($request->all(),[
        'morada' => 'required|string',
        'codigo_postal' => 'required|string',
        'localidade' => 'required|string',
        'unidade' => 'required|string|unique:police_station',
        'sigla' => 'required|string|unique:police_station',
        'telefone' => 'required|string',
        'fax' => 'required|string',
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

    return  redirect()->route('stations.store');

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
    return view('policesform' , ['users' => $user]);
    
}



public function destroy(string $id) {
    PoliceStation::where('_id' ,$id )->delete();
    return redirect()->route('stations.store');
}


public function edit(PoliceStation $user) {

    session(['sigla' => $user->sigla]);

    return view('stationeditform' , ['user' => $user]);

    

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