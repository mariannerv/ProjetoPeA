<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;    
use App\Models\foundObject;
use Illuminate\Support\Facades\Validator;
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
        $val = Validator::make($request->all(),[
            'category' => 'required|string',
            'brand' => 'required|string',
            'color' => 'required|string',
            'size' => 'required|string',
            'description' => 'required|string',
            'address' => 'required|string',
            'location' => 'required|string',
            'postalcode' => 'required|string',
            'name' => 'required|string',
            'number' => 'required|string',
            'email' => 'required|string',
            'location_coords' => [
                'nullable',
                'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?),\s*[-]?((([1]?[0-7]?[0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?$/'
            ],
            'date_found' => 'required|date',
            'policeStationId' => 'required|string|exists:police_station,sigla',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($val->fails()){
            return redirect()
                ->back()
                ->withErrors($val)
                ->withInput();
        }

        $dateRegistered = (string) now();
        $deadlineForAuction = (string) now()->addMonth();
        $uuid = (string) Str::uuid();

        $imageName = null;
        if ($request->hasFile('img')) {
            $imageName = time().'_'.uniqid().'.'.$request->img->extension();
            $request->img->move(public_path('images/found-objects-img'), $imageName);
        }

        $foundObject = FoundObject::create([
            "category" => $request->category,
            "brand" => $request->brand,
            "color" => $request->color,
            "size" => $request->size,
            "description" => $request->description,
            "location_coords" => $request->location_coords,
            'address' => $request->address,
            'location' => $request->location,
            'postalcode' => $request->postalcode,
            "objectId" => $uuid,
            "name" => $request->name,
            "email" => $request->email,
            "number" => $request->number,
            "date_found" => $request->date_found,
            "date_registered" => $dateRegistered,
            "deadlineForAuction" => $deadlineForAuction,
            "estacao_policia" => $request->policeStationId,
            "possible_owner" => [],
            "image" => $imageName     
        ]);

        return redirect()->back()->with('success', 'Objeto encontrado registado com sucesso');

    } catch (\Exception $e) {
        return response()->json([
            "status" => false,
            "message" => "Algo correu mal ao registar o objeto.",
            "code" => "404",
        ]);
    }
}

    public function getFoundObject($objectId)
    {
        try {

            $object = FoundObject::where('objectId', $objectId)->first();

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

            if(isset($request->img)) {
                $imageName = null;
                if ($request->hasFile('img')) {
                    $imageName = time().'_'.uniqid().'.'.$request->img->extension();
                    $request->img->move(public_path('images/found-objects-img'), $imageName);
                }
    
                $object->update([
                "category" => $request->category,
                "brand" => $request->brand,
                "color" => $request->color,
                "size" => $request->size,
                "description" => $request->description,
                "location_coords" => $request->location_coords,
                'address' => $request->address,
                'location' => $request->location,
                'postalcode' => $request->postalcode,
                "name" => $request->name,
                "email" => $request->email,
                "number" => $request->number,
                "date_found" => $request->date_found,
                "estacao_policia" => $request->policeStationId,
                "image" => $imageName     
    
                ]);
            }
            else  {
                $object->update([
                    "category" => $request->category,
                    "brand" => $request->brand,
                    "color" => $request->color,
                    "size" => $request->size,
                    "description" => $request->description,
                    "location_coords" => $request->location_coords,
                    'address' => $request->address,
                    'location' => $request->location,
                    'postalcode' => $request->postalcode,
                    "name" => $request->name,
                    "email" => $request->email,
                    "number" => $request->number,
                    "date_found" => $request->date_found,
                    "estacao_policia" => $request->policeStationId,
                  
        
                    ]);
            }
            

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

    public function deleteFoundObject($id)
    {
        try {
           
            $object = FoundObject::where('_id', $id)->first();

            if ($object) {
                $object->delete();
                return redirect()->back()->with('success', 'Objeto encontrado registado com sucesso');
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
            $foundObjects = FoundObject::orderBy('created_at', 'desc')->get();

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

    public function edit(FoundObject $object) {
        
        return view('objects.foundobjectedit' , ['object' => $object]);
    }

 
    public function getobject(FoundObject $object) {
        return view('objects.found-object' , ['object' => $object]);
    }
    
    public function update(Request $request, string $id) {
      //  $update = User::where('_id' , $id)->update(

        try {
            $val = Validator::make($request->all(),[
                'category' => 'required|string',
                'brand' => 'required|string',
                'color' => 'required|string',
                'size' => 'required|string',
                'description' => 'required|string',
                'location_id' => 'nullable|string',
                'location_coords' => [
                    'nullable',
                    'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?),\s*[-]?((([1]?[0-7]?[0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?$/'
                ],
                'date_found' => 'required|date',
                'policeStationId' => 'required|string|exists:police_station,sigla',
            ]);

            if ($val->fails()){
                return redirect()
                ->back()
                ->withErrors($val)
                ->withInput();
            }

            
            $object = FoundObject::where('_id', $id)->first();
            
            if(isset($request->img)) {
                $imageName = null;
                if ($request->hasFile('img')) {
                    $imageName = time().'_'.uniqid().'.'.$request->img->extension();
                    $request->img->move(public_path('images/found-objects-img'), $imageName);
                }
    
                $object->update([
                "category" => $request->category,
                "brand" => $request->brand,
                "color" => $request->color,
                "size" => $request->size,
                "description" => $request->description,
                "location_coords" => $request->location_coords,
                'address' => $request->address,
                'location' => $request->location,
                'postalcode' => $request->postalcode,
                "name" => $request->name,
                "email" => $request->email,
                "number" => $request->number,
                "date_found" => $request->date_found,
                "estacao_policia" => $request->policeStationId,
                "image" => $imageName     
    
                ]);
            }
            else  {
                $object->update([
                    "category" => $request->category,
                    "brand" => $request->brand,
                    "color" => $request->color,
                    "size" => $request->size,
                    "description" => $request->description,
                    "location_coords" => $request->location_coords,
                    'address' => $request->address,
                    'location' => $request->location,
                    'postalcode' => $request->postalcode,
                    "name" => $request->name,
                    "email" => $request->email,
                    "number" => $request->number,
                    "date_found" => $request->date_found,
                    "estacao_policia" => $request->policeStationId,
                  
        
                    ]);
            }
            

            return redirect()->back()->with('success', 'Objeto encontrado editado com sucesso');
          
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Algo correu mal ao registar o objeto.",
                "code" => "404",
            ]);
        }
      
    }


    public function deleteFoundObject2(Request $request)
    {
        try {
            $objectId = $request->objectId;
            $object = FoundObject::where('objectId', $objectId)->first();

            if ($object) {
                $object->delete();
                return redirect()->route('home');
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

    public function getall() {
        $foundObjects = FoundObject::all();

        return view('objects.found-objects.auctions-register' , ['foundObjects' => $foundObjects]);
    }

    public function search($id) {
        $foundObjects = FoundObject::all();

        return view('objects.search' , ['foundObjects' => $foundObjects , 'id' => $id]);
    }
}


