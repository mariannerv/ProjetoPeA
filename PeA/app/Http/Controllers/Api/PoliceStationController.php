<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PoliceStation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PoliceStationController extends Controller
{
    public function registerPost(Request $request){

    try {
    $request->validate([
        'morada' => 'required|string',
        'codigo_postal' => 'required|string',
        'localidade' => 'required|string',
        'unidade' => 'required|string|unique:police_station',
        'sigla' => 'required|string|unique:police_station',
        'telefone' => 'required|string',
        'fax' => 'required|string',
        'email' => 'required|email',
    ]);

    PoliceStation::create([
        "morada" => $request->morada,
        "codigo_postal" => $request->codigo_postal,
        "localidade" => $request->localidade,
        "unidade" => $request->unidade,
        "sigla" => $request->sigla,
        "telefone" => $request->telefone,
        "fax" => $request->fax,
        "email" => $request->email,
    ]);

    return response()->json([
        "status" => true,
        "message" => "Posto registado com sucesso",
        "code" => "200",
    ]);
} catch (ValidationException $e) {
    if ($e->errors()['unidade'] && $e->errors()['unidade'][0] === 'The unidade has already been taken.') {
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


}
