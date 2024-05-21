<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FoundObject;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Carbon;
use MongoDB\BSON\UTCDateTime;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\User;

class FoundObjectController extends Controller
{
    public function registerFoundObject(Request $request)
    {
        try {
            $request->validate([
                'categoryId' => 'required|string',
                'brand' => 'nullable|string',
                'color' => 'nullable|string',
                'size' => 'nullable|string',
                'description' => 'required|string',
                'location_id' => 'required|string',
                'location_coords' => [
                    'nullable',
                    'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?),\s*[-]?((([1]?[0-7]?[0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?$/'
                ],
                'date_found' => 'required|date',
                'police_station' => 'required|string|exists:police_station,sigla',
            ]);

            $dateRegistered = now();
            $deadlineForAuction = now()->addMonth();
            $uuid = (string) Str::uuid();

            FoundObject::create([
                "categoryId" => $request->categoryId,
                "brand" => $request->brand,
                "color" => $request->color,
                "size" => $request->size,
                "description" => $request->description,
                "location_id" => $request->location_id,
                "location_coords" => $request->location_coords,
                "date_found" => $request->date_found,
                "date_registered" => $dateRegistered,
                "deadlineForAuction" => $deadlineForAuction,
                "police_station" => $request->police_station,
            ]);

            return response()->json([
                "status" => true,
                "message" => "Objeto encontrado registado com sucesso",
                "code" => "200",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Algo correu mal ao registar o objeto.",
                "code" => "404",
            ]);
        }
    }

    public function getFoundObject(Request $request)
    {
        try {
            $request->validate([
                'objectId' => 'required|string',
            ]);

            $object = FoundObject::where('objectId', $request->objectId)->first();

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
        $object = FoundObject::where('objectId', $request->objectId)->first();

        if ($object) {
            $request->validate([
                'categoryId' => 'string',
                'brand' => 'string',
                'color' => 'string',
                'size' => 'string',
                'description' => 'string',
                'location_id' => 'string',
                'location_coords' => [
                    'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?),\s*[-]?((([1]?[0-7]?[0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?$/'
                ],
                'date_found' => 'date',
                'deadlineForAuction' => 'date',
                'police_station' => 'string|exists:police_station,sigla',
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

    public function deleteFoundObject(Request $request)
    {
        try {
            $objectId = $request->objectId;
            $object = FoundObject::where('objectId', $objectId)->first();

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

    public function getAllFoundObjects()
    {
        try {
            $foundObjects = FoundObject::all();

            return response()->json([
                "status" => true,
                "data" => $foundObjects,
                "code" => 200,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "An error occurred while fetching all found objects.",
                "code" => 500,
            ], 500);
        }
    }

    public function getStatistics()
    {
        try {
            // Group found objects by category and date found
            $statistics = DB::table('FoundObject')
                ->select('categoryId', DB::raw('count(*) as count'))
                ->groupBy('categoryId')
                ->get();

            $data = [
                'categories' => $statistics->pluck('categoryId'),
                'counts' => $statistics->pluck('count')
            ];

            return response()->json([
                "status" => true,
                "data" => $data,
                "code" => 200,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "An error occurred while fetching statistics for found objects.",
                "code" => 500,
            ], 500);
        }
    }


    public function searchByDescription(Request $request)
    {
        try {
            $request->validate([
                'description' => 'required|string',
            ]);

            $objects = FoundObject::where('description', 'like', '%' . $request->description . '%')->get();

            return response()->json([
                "status" => true,
                "data" => $objects,
                "code" => 200,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "An error occurred while searching for found objects.",
                "code" => 500,
            ], 500);
        }
    }
    
}
##ccccc changeess