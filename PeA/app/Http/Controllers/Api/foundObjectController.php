<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\foundObject;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Carbon;
use MongoDB\BSON\UTCDateTime;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\User;

class foundObjectController extends Controller
{
public function registerFoundObject(Request $request)
{
    try {
        $request->validate([
            'category' => 'required|string',
            'description' => 'required|string',
            'location' => 'required|string',
            'location_coords' => [
                'nullable',
                'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?),\s*[-]?((([1]?[0-7]?[0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?$/'
            ],
            'date_found' => 'required|date',
        ]);
            

        $dateRegistered = new UTCDateTime(now()->timestamp * 1000);
        $deadlineForAuction = new UTCDateTime(now()->addMonth()->timestamp * 1000);
        $uuid = (string) Str::uuid();

        foundObject::create([
            "objectId" => $uuid,
            "possible_owner" => '',
            "category" => $request->category,
            "description" => $request->description,
            "location" => $request->location,
            "location_coords" => $request->location_coords,
            "value" => 0,
            "date_found" => $request->date_found,
            "date_registered" => $dateRegistered,
            "deadlineForAuction" => $deadlineForAuction,
        ]);

        return response()->json([
            "status" => true,
            "message" => "Objeto encontrado registado com sucesso",
            "code" => "200",
        ]);
    } catch (ValidationException $e) {
        return response()->json([
            "status" => false,
            "message" => "Algo correu mal ao registar o objeto.",
            "code" => "404",
        ]);
    }
}

public function viewFoundObject(Request $request){
        try {
            $request->validate([
                'objectId' => 'required|string',
            ]);

            $object = foundObject::where('objectId', $request->objectId)->first();

            if ($object) {
              
                return response()->json([
                    "status" => true,
                    "data" => $object,
                    "code" => 200,
                ]);
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "Objeto não encontrado.",
                    "code" => 404,
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Ocorreu um erro ao recuperar as informações do objeto.",
                "code" => 500,
            ], 500);
        }
    }

   public function updateFoundObject(Request $request)
{
    $object = foundObject::where('objectId', $request->objectId)->first();
    
    if ($object) {
        $request->validate([
                'possible_owner' => [
                'string',
                Rule::exists(User::class, 'email'), //Para ter a certeza que o possible_owner existe na bd
            ],
            'category' => 'string',
            'description' => 'string',
            'location' => 'string',
            'location_coords' => [
                'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?),\s*[-]?((([1]?[0-7]?[0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?$/'
            ],
            'value' => 'numeric|min:0',
            'date_found' => 'date',
            'deadlineForAuction' => 'date',
        ]);

        $object->update($request->all());

        return response()->json([
            "status" => true,
            "code" => 200,
            "message" => "Objeto atualizado com sucesso.",
        ]);
    } else {
        return response()->json([
            "status" => false,
            "code" => 404,
            "message" => "Objeto não encontrado.",
        ], 404);
    }
}

public function deleteFoundObject(Request $request){
        try {
            $objectId = $request->objectId;
            $object = foundObject::where('objectId', $objectId)->first();

        if ($object) {
            $object->delete();
            return response()->json([
                "status" => true,
                "message" => "Objeto apagado com sucesso.",
                "code" => "200",
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Objeto não encontrado.",
                "code" => "404",
            ], 404);
        }
    } catch (\Exception $e) {
        return response()->json([
            "status" => false,
            "message" => "Oops! Algo correu mal ao tentar apagar o objeto.",
            "code" => "500",
        ], 500);
    }
}
}















